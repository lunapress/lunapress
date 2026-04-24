<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use BackedEnum;
use LunaPress\CoreContracts\Hook\ActionManager;
use LunaPress\FoundationContracts\Support\Wp\WpCaster;
use LunaPress\Frontend\Modules\Vite\Constants;
use LunaPress\FrontendContracts\Vite\DTO\ViteAsset;
use LunaPress\FrontendContracts\Vite\DTO\ViteConfig;
use LunaPress\FrontendContracts\Vite\ViteAssetsLoader;
use LunaPress\FrontendContracts\Vite\ViteEnvDetector;
use LunaPress\FrontendContracts\Vite\ViteManifestReader;
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

final readonly class DefaultViteAssetsLoader implements ViteAssetsLoader
{
    public function __construct(
        private ViteEnvDetector $viteEnvDetector,
        private ViteManifestReader $viteManifestReader,
        private ActionManager $actionManager,
        private WpEnqueueScriptModule $enqueueScriptModule,
        private WpRegisterScript $registerScript,
        private WpEnqueueStyle $enqueueStyle,
        private WpEnqueueScript $enqueueScript,
        private ViteConfig $config,
        private WpCaster $caster
    ) {
    }

    /**
     * @inheritDoc
     */
    public function connect(array $assets, bool $isAdmin = false, array $dependencies = []): void
    {
        $normalizedDependencies = $this->normalizeDependencies($dependencies);

        if ($this->viteEnvDetector->isDev()) {
            $this->connectDev($assets, $isAdmin);
        } else {
            $this->connectProd($assets, $normalizedDependencies);
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
     * @param ViteAsset[] $assets
     */
    private function connectDev(array $assets, bool $isAdmin): void
    {
        $hook = $isAdmin ? 'admin_footer' : 'wp_footer';

        $this->actionManager->add($hook, function () use ($assets): void {
            echo $this->devScriptsHtml($assets);
        });
    }

    /**
     * @param ViteAsset[] $assets
     */
    private function devScriptsHtml(array $assets): string
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

        foreach ($assets as $asset) {
            // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
            $scripts .= "\n<script type=\"module\" src=\"{$host}/{$asset->name}\"></script>";
        }

        return $scripts;
    }

    /**
     * @param ViteAsset[] $assets
     * @param array<ScriptModuleDependency|string|BackedEnum> $allDependencies
     */
    private function connectProd(array $assets, array $allDependencies): void
    {
        $manifest = $this->viteManifestReader->getManifest();
        $version  = $this->config->pluginVersion;
        $baseUrl  = rtrim($this->config->buildViteUrl, '/');
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

        foreach ($assets as $asset) {
            $manifestItem = $manifest->getItem(
                $this->caster->asString($asset->name)
            );

            if ($manifestItem === null) {
                throw new RuntimeException("Vite entry '{$asset->name}' not found in manifest.");
            }

            $fileUrl = "{$baseUrl}/{$manifestItem->file}";

            // JS
            if (!$manifestItem->isCss) {
                $this->connectDependencies($dependencies);

                ($this->enqueueScriptModule)(
                    id: $manifestItem->name,
                    src: $fileUrl,
                    deps: $moduleDependencies,
                    version: $version,
                );

                ($this->registerScript)(
                    handle: $manifestItem->name,
                    src: $fileUrl,
                    deps: $dependencies,
                    version: $version,
                    args: true
                );
            }

            // CSS
            foreach ($manifestItem->css as $css) {
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
