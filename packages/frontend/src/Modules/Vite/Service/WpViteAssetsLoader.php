<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use LunaPress\CoreContracts\Hook\IActionManager;
use LunaPress\FoundationContracts\Support\WpFunction\IWpFunctionExecutor;
use LunaPress\FrontendContracts\Vite\IViteEntryPoint;
use LunaPress\Wp\AssetsContracts\IAssetDependency;
use LunaPress\Wp\AssetsContracts\IAssetDependencyFactory;
use LunaPress\Wp\AssetsContracts\WpAssetHandle;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDep;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueStyle\IWpEnqueueStyleFactory;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptFactory;
use LunaPress\Frontend\Modules\Vite\Constants;
use LunaPress\FrontendContracts\Vite\IViteAssetsLoader;
use LunaPress\FrontendContracts\Vite\IViteConfig;
use LunaPress\FrontendContracts\Vite\IViteManifestReader;
use LunaPress\FrontendContracts\Vite\IViteModeDetector;
use RuntimeException;

defined('ABSPATH') || exit;

final readonly class WpViteAssetsLoader implements IViteAssetsLoader
{
    public function __construct(
        private IViteModeDetector $viteModeDetector,
        private IViteManifestReader $viteManifestReader,
        private IActionManager $actionManager,
        private IWpFunctionExecutor $wpFunctionExecutor,
        private IWpEnqueueScriptModuleFactory $enqueueScriptModuleFactory,
        private IWpRegisterScriptFactory $registerScriptFactory,
        private IWpEnqueueStyleFactory $enqueueStyleFactory,
        private IAssetDependencyFactory $assetDependencyFactory,
        private IWpEnqueueScriptFactory $enqueueScriptFactory,
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
     * @param array<IWpEnqueueScriptModuleDep|IAssetDependency> $rawDependencies
     * @return array<IWpEnqueueScriptModuleDep|IAssetDependency>
     */
    private function normalizeDependencies(array $rawDependencies): array
    {
        $dependencies = $rawDependencies;

        if (count($rawDependencies) === 0) {
            /**
             * @var WpAssetHandle $handle
             */
            $dependencies = array_map(fn($handle) => $this->assetDependencyFactory->make($handle->value), Constants::DEFAULT_FRONTEND_DEPS);
        }

        return $dependencies;
    }

    /**
     * @param array<IAssetDependency|IWpEnqueueScriptModuleDep> $dependencies
     * @return void
     */
    private function connectDependencies(array $dependencies): void
    {
        foreach ($dependencies as $dependency) {
            if (!($dependency instanceof IAssetDependency)) {
                continue;
            }

            $this->wpFunctionExecutor->execute(
                $this->enqueueScriptFactory
                    ->make($dependency->getHandle())
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
     * @return string
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
     * @param array<IWpEnqueueScriptModuleDep|IAssetDependency> $dependencies
     */
    private function connectProd(array $entryPoints, array $dependencies): void
    {
        $manifest = $this->viteManifestReader->getManifest();
        $version  = $this->config->getPluginVersion();
        $baseUrl  = rtrim($this->config->getBuildViteUrl(), '/');

        foreach ($entryPoints as $entryPoint) {
            $entry = $manifest->getEntry($entryPoint->getName());

            if ($entry === null) {
                throw new RuntimeException("Vite entry '{$entryPoint->getName()}' not found in manifest.");
            }

            $fileUrl = "{$baseUrl}/{$entry->getFile()}";

            // JS
            if (!preg_match('/\.css$/', $entry->getFile())) {
                /**
                 * @var IWpEnqueueScriptModuleDep[] $moduleDependencies
                 */
                $moduleDependencies = array_filter(
                    $dependencies,
                    fn($dependency) => $dependency instanceof IWpEnqueueScriptModuleDep,
                );

                $this->connectDependencies($dependencies);

                $this->wpFunctionExecutor->execute(
                    $this->enqueueScriptModuleFactory
                        ->make($entry->getName())
                        ->src($fileUrl)
                        ->deps($moduleDependencies)
                        ->version($version)
                );

                $this->wpFunctionExecutor->execute(
                    $this->registerScriptFactory
                        ->make($entry->getName(), $fileUrl)
                        ->deps($dependencies)
                        ->version($version)
                        ->args(true)
                );
            }

            // CSS
            foreach ($entry->getCss() as $css) {
                $this->wpFunctionExecutor->execute(
                    $this->enqueueStyleFactory
                        ->make($css)
                        ->src("{$baseUrl}/{$css}")
                        ->deps($dependencies)
                        ->version($version)
                        ->media('all')
                );
            }
        }
    }
}
