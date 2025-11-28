<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Generator;

interface IPotGenerator
{
    /**
     * @param string $source
     * @param string $destinationDir
     * @param string[] $domains
     * @param string[] $ignoreDomains
     * @return void
     */
    public function generate(string $source, string $destinationDir, array $domains = [], array $ignoreDomains = []): void;
}
