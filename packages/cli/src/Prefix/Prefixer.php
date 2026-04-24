<?php

declare(strict_types=1);

namespace LunaPress\Cli\Prefix;

use LunaPress\Cli\Prefix\Exceptions\PrefixException;
use Symfony\Component\Console\Style\SymfonyStyle;

interface Prefixer
{
    /**
     * @param array<string, mixed> $config Strauss-compatible configuration array
     * @throws PrefixException
     */
    public function prefix(string $targetPath, array $config, SymfonyStyle $io): void;
}
