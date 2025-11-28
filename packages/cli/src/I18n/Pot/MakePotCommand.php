<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot;

use LunaPress\Cli\I18n\Pot\Generator\IPotGenerator;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Path;

#[AsCommand(
    name: 'i81n:make-pot',
    description: 'Generates .pot for translation'
)]
final class MakePotCommand extends Command
{
    public function __construct(
        private readonly IPotGenerator $generator
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,

        #[Argument(description: 'Directory to scan')]
        ?string $source = null,

        #[Argument(description: 'Path for .POT output files')]
        ?string $destination = null,

        #[Option(description: 'Consider only specific domains', shortcut: 'd')]
        array $domains = [],

        #[Option(description: 'Ignore domains')]
        array $ignoreDomains = []
    ): int {
        $this->generator->generate(
            $this->normalizeSource($source),
            $this->normalizeDestination($destination),
            $domains,
            $ignoreDomains,
        );

        $io->success('Successfully completed');

        return Command::SUCCESS;
    }

    private function normalizeDestination(?string $destination): string
    {
        return $destination ?? Path::join(getcwd(), 'languages');
    }

    private function normalizeSource(?string $source): string
    {
        return $source ?? getcwd();
    }
}
