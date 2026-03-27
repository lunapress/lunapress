<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Generator;

use Symfony\Component\Console\Style\SymfonyStyle;

interface IPotGenerator
{
    /**
     * @param string $sourceDir
     * @param string $destinationDir
     * @param SymfonyStyle $io
     * @param string[] $domains
     * @param string[] $ignoreDomains
     * @param string[] $include
     * @param string[] $exclude
     * @param bool $skipFrontend
     * @param string|null $cliVersion
     * @return void
     */
    public function generate(
        string $sourceDir,
        string $destinationDir,
        SymfonyStyle $io,
        array  $domains = [],
        array  $ignoreDomains = [],
        array  $include = [],
        array  $exclude = [],
        bool   $skipFrontend = false,
        ?string $cliVersion = null,
    ): void;
}
