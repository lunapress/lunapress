<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use LunaPress\CoreContracts\Hook\IActionManager;
use LunaPress\CoreContracts\Support\WpFunction\IWpFunctionExecutor;
use LunaPress\Wp\AssetsContracts\IAssetDependency;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\Enum\WpEnqueueScriptModuleImport;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDepsFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueStyle\IWpEnqueueStyleFactory;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptFactory;
use LunaPress\Frontend\Modules\Vite\Constants;
use LunaPress\FrontendContracts\Vite\IViteAssetsLoader;
use LunaPress\FrontendContracts\Vite\IViteConfig;
use LunaPress\FrontendContracts\Vite\ViteEntryPoint;
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
        private IWpEnqueueScriptModuleDepsFactory $wpEnqueueScriptModuleDepsFactory,
        private IViteConfig $config,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function connect(array $entryPoints, array $dependency = [], bool $isAdmin = false): void
    {
        if ($this->viteModeDetector->isDev()) {
            $this->connectDev($entryPoints, $isAdmin);
            return;
        }

        $this->connectProd($entryPoints, $dependency);
    }

    /**
     * @param ViteEntryPoint[] $entryPoints
     */
    private function connectDev(array $entryPoints, bool $isAdmin): void
    {
        $hook = $isAdmin ? 'admin_footer' : 'wp_footer';

        $this->actionManager->add($hook, function () use ($entryPoints): void {
            echo $this->devScriptsHtml($entryPoints);
        });
    }

    /**
     * @param ViteEntryPoint[] $entryPoints
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
     * @param ViteEntryPoint[] $entryPoints
     * @param IAssetDependency[] $dependencies
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
                $moduleDependencies = array_map(
                    fn($dependency) =>
                    $this->wpEnqueueScriptModuleDepsFactory
                        ->make()
                        ->id($dependency->getHandle())
                        ->import(WpEnqueueScriptModuleImport::STATIC),
                    $dependencies
                );

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
