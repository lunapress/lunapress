<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot;

use LunaPress\Cli\I18n\Pot\Generator\IPotGenerator;
use LunaPress\Cli\Support\IPathResolver;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'i18n:make-pot',
    description: 'Generates .pot for translation',
)]
final class MakePotCommand extends Command
{
    public function __construct(
        private readonly IPotGenerator $generator,
        private readonly IPathResolver $pathResolver,
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
        array $ignoreDomains = [],

        #[Option(description: 'Scan additional paths (syntax `symfony/finder`)')]
        array $include = [],

        #[Option(description: 'Ignore paths (syntax `symfony/finder`)')]
        array $exclude = [],

        #[Option(description: 'Skips TypeScript string extraction')]
        bool  $skipFrontend = false,
    ): int {
        $this->generator->generate(
            $this->pathResolver->projectPath($source),
            $this->pathResolver->languages($destination),
            $io,
            $domains,
            $ignoreDomains,
            $include,
            $exclude,
            $skipFrontend,
            $this->getApplication()?->getVersion(),
        );

        $io->success('Successfully completed');

        return Command::SUCCESS;
    }
}
