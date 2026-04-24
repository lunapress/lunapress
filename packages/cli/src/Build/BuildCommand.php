<?php

declare(strict_types=1);

namespace LunaPress\Cli\Build;

use LunaPress\Cli\Support\PathResolver;
use LunaPress\Config\ConfigResolver;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'build',
    description: 'Builds a ZIP with vendor prefix'
)]
class BuildCommand extends Command
{
    public function __construct(
        private readonly ConfigResolver $configResolver,
        private readonly PathResolver   $pathResolver,
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,

        #[Argument(description: 'Directory to archive')]
        ?string $source = null,

        #[Option(description: 'Output path for the ZIP archive', name: 'output', shortcut: 'o')]
        ?string $output = null,
    ): int {
        return Command::SUCCESS;
    }
}
