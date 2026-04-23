<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use BackedEnum;
use LunaPress\CoreContracts\Hook\IActionManager;
use LunaPress\Frontend\Modules\Vite\Constants;
use LunaPress\FrontendContracts\Vite\IViteAssetsLoader;
use LunaPress\FrontendContracts\Vite\IViteConfig;
use LunaPress\FrontendContracts\Vite\IViteEntryPoint;
use LunaPress\FrontendContracts\Vite\IViteManifestReader;
use LunaPress\FrontendContracts\Vite\IViteModeDetector;
use LunaPress\Wp\Assets\Function\WpEnqueueScript;
use LunaPress\Wp\Assets\Function\WpEnqueueScriptModule;
use LunaPress\Wp\Assets\Function\WpEnqueueStyle;
use LunaPress\Wp\Assets\Function\WpRegisterScript;
use LunaPress\Wp\AssetsContracts\DTO\ScriptModuleDependency;
use LunaPress\Wp\AssetsContracts\Enum\WpAssetHandle;
use RuntimeException;
use function array_filter;
use function array_values;
use function count;
use function is_string;
use function rtrim;

final readonly class WpViteAssetsLoader implements IViteAssetsLoader
{
    public function __construct(
        private IViteModeDetector $viteModeDetector,
        private IViteManifestReader $viteManifestReader,
        private IActionManager $actionManager,
        private WpEnqueueScriptModule $enqueueScriptModule,
        private WpRegisterScript $registerScript,
        private WpEnqueueStyle $enqueueStyle,
        private WpEnqueueScript $enqueueScript,
        private IViteConfig $config,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function connect(array $entryPoints, bool $isAdmin = false, array $dependencies = []): void
    {
        $normalizedDependencies = $this->normalizeDependencies($dependencies);

        if ($this->viteModeDetector->isDev()) {
            $this->connectDev($entryPoints, $isAdmin);
        } else {
            $this->connectProd($entryPoints, $normalizedDependencies);
        }
    }

    /**
     * @param array<ScriptModuleDependency|string|BackedEnum> $rawDependencies
     * @return array<ScriptModuleDependency|string|BackedEnum>
     */
    private function normalizeDependencies(array $rawDependencies): array
    {
        $dependencies = $rawDependencies;

        if (count($rawDependencies) === 0) {
            /**
             * @var WpAssetHandle[]|string[] $dependencies
             */
            $dependencies = Constants::DEFAULT_FRONTEND_DEPS;
        }

        return $dependencies;
    }

    /**
     * @param array<string|BackedEnum> $dependencies
     */
    private function connectDependencies(array $dependencies): void
    {
        foreach ($dependencies as $dependency) {
            ($this->enqueueScript)(
                handle: $dependency
            );
        }
    }

    /**
     * @param IViteEntryPoint[] $entryPoints
     */
    private function connectDev(array $entryPoints, bool $isAdmin): void
    {
        $hook = $isAdmin ? 'admin_footer' : 'wp_footer';

        $this->actionManager->add($hook, function () use ($entryPoints): void {
            echo $this->devScriptsHtml($entryPoints);
        });
    }

    /**
     * @param IViteEntryPoint[] $entryPoints
     */
    private function devScriptsHtml(array $entryPoints): string
    {
        $host = Constants::HMR_HOST;

        // phpcs:disable
        $scripts = <<<HTML
            <script type="module">
                import RefreshRuntime from '{$host}/@react-refresh'
                RefreshRuntime.injectIntoGlobalHook(window)
                window.\$RefreshReg$ = () => {}
                window.\$RefreshSig$ = () => (type) => type
                window.__vite_plugin_react_preamble_installed__ = true
            </script>
            <script src="{$host}/@vite/client" type="module"></script>
        HTML;
        // phpcs:enable

        foreach ($entryPoints as $entry) {
            // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
            $scripts .= "\n<script type=\"module\" src=\"{$host}/{$entry->getName()}\"></script>";
        }

        return $scripts;
    }

    /**
     * @param IViteEntryPoint[] $entryPoints
     * @param array<ScriptModuleDependency|string|BackedEnum> $allDependencies
     */
    private function connectProd(array $entryPoints, array $allDependencies): void
    {
        $manifest = $this->viteManifestReader->getManifest();
        $version  = $this->config->getPluginVersion();
        $baseUrl  = rtrim($this->config->getBuildViteUrl(), '/');
        /**
         * @var ScriptModuleDependency[] $moduleDependencies
         */
        $moduleDependencies = array_filter(
            $allDependencies,
            fn($dependency) => $dependency instanceof ScriptModuleDependency,
        );
        /**
         * @var array<string|BackedEnum> $dependencies
         */
        $dependencies = array_values(
            array_filter(
                $allDependencies,
                static fn(mixed $dependency): bool => is_string($dependency) || $dependency instanceof BackedEnum
            )
        );

        foreach ($entryPoints as $entryPoint) {
            $entry = $manifest->getEntry($entryPoint->getName());

            if ($entry === null) {
                throw new RuntimeException("Vite entry '{$entryPoint->getName()}' not found in manifest.");
            }

            $fileUrl = "{$baseUrl}/{$entry->getFile()}";

            // JS
            if (!$entry->isCss()) {
                $this->connectDependencies($dependencies);

                ($this->enqueueScriptModule)(
                    id: $entry->getName(),
                    src: $fileUrl,
                    deps: $moduleDependencies,
                    version: $version,
                );

                ($this->registerScript)(
                    handle: $entry->getName(),
                    src: $fileUrl,
                    deps: $dependencies,
                    version: $version,
                    args: true
                );
            }

            // CSS
            foreach ($entry->getCss() as $css) {
                ($this->enqueueStyle)(
                    handle: $css,
                    src: "{$baseUrl}/{$css}",
                    deps: $dependencies,
                    version: $version,
                );
            }
        }
    }
}
