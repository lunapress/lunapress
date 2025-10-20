<?php
declare(strict_types=1);

namespace LunaPress\Cli\Frontend\Init;

use LunaPress\Cli\Frontend\FrontendFramework;
use LunaPress\Cli\Frontend\PackageManager;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

defined('ABSPATH') || exit;

#[AsCommand(
    name: 'frontend:init',
    description: 'Initialize a new frontend project'
)]
final class FrontendInitCommand extends Command
{
    private FrontendInitConfig $config;

    public function __construct(
        private readonly IFrontendProjectGenerator $generator = new FrontendProjectGenerator(),
    ) {
        parent::__construct();
    }

    #[Override]
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
    }

    #[Override]
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $selectedFramework = $io->choice(
            'Framework',
            array_map(fn($framework) => $framework->value, FrontendFramework::cases()),
            FrontendFramework::React->value
        );

        $useTailwind = $io->confirm(
            'Tailwind',
            FrontendInitConfig::DEFAULT_USE_TAILWIND
        );

        $selectedPackageManager = $io->choice(
            'Package manager',
            array_map(fn($manager) => $manager->value, PackageManager::cases()),
            PackageManager::Pnpm->value
        );

        $directory = $io->ask(
            'Frontend folder',
            FrontendInitConfig::DEFAULT_DIRECTORY
        );

        $this->config = new FrontendInitConfig(
            FrontendFramework::from($selectedFramework),
            $useTailwind,
            PackageManager::from($selectedPackageManager),
            $directory
        );
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->generator->generate($this->config);

        $io->success('Frontend initialization complete.');
        return Command::SUCCESS;
    }

    /**
     * @return FrontendInitConfig
     */
    public function getConfig(): FrontendInitConfig
    {
        return $this->config;
    }
}
