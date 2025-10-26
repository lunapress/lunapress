<?php
declare(strict_types=1);

namespace LunaPress\Cli\Robo;

use LunaPress\Cli\Frontend\FrontendFramework;
use LunaPress\Cli\Frontend\Init\FrontendInitConfig;
use LunaPress\Cli\Frontend\Init\FrontendProjectGenerator;
use LunaPress\Cli\Frontend\PackageManager;
use LunaPress\Cli\Support\PathResolver;
use ReflectionClass;

final class Tasks extends \Robo\Tasks
{
    public function frontendPreview(string $framework = 'React', string $manager = 'pnpm'): void
    {
        $config = new FrontendInitConfig(
            FrontendFramework::from($framework),
            true,
            PackageManager::from($manager),
            '.frontend-preview'
        );

        $pathResolver = new PathResolver(
            null,
            new WorkingDirectory()
        );

        $targetPath = $pathResolver->frontendInitPath($config);
        $this->_deleteDir($targetPath);

        (new FrontendProjectGenerator(
            $pathResolver
        ))->generate($config);

        $this->say('Preview generated in .frontend-preview/');
    }
}

class_alias(Tasks::class, (new ReflectionClass(Tasks::class))->getShortName());
