<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Generator;

interface IPotGenerator
{
    /**
     * @param string $sourceDir
     * @param string $destinationDir
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
        array  $domains = [],
        array  $ignoreDomains = [],
        array  $include = [],
        array  $exclude = [],
        bool   $skipFrontend = false,
        ?string $cliVersion = null,
    ): void;
}
