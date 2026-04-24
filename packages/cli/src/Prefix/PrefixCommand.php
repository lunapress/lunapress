<?php

declare(strict_types=1);

namespace LunaPress\Cli\Prefix;

use LunaPress\Cli\Prefix\Exceptions\PrefixException;
use LunaPress\Cli\Support\PathResolver;
use LunaPress\Config\ConfigResolver;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'vendor:prefix',
    description: 'Prefixes namespaces with Strauss'
)]
final class PrefixCommand extends Command
{
    public function __construct(
        private readonly Prefixer       $prefixer,
        private readonly ConfigResolver $configResolver,
        private readonly PathResolver   $pathResolver,
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,

        #[Argument(description: 'Directory for prefixes')]
        ?string $source = null,
    ): int {
        $sourcePath = $this->pathResolver->projectPath($source);

        $projectConfig = $this->configResolver->resolve($sourcePath);
        $straussConfig = $projectConfig->getStraussConfig();

        if (empty($straussConfig)) {
            $io->error('Strauss configuration not found in .lunapress.php');
            return Command::FAILURE;
        }

        try {
            $this->prefixer->prefix($sourcePath, $straussConfig, $io);
        } catch (PrefixException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success('Successfully completed');

        return Command::SUCCESS;
    }
}
