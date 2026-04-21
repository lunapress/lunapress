<?php

declare(strict_types=1);

namespace LunaPress\Cli\Build\Archive;

use LunaPress\Cli\Build\Archive\Exceptions\ArchiveException;
use Symfony\Component\Console\Style\SymfonyStyle;

interface IArchiver
{
    /**
     * @throws ArchiveException
     */
    public function archive(string $absoluteSourcePath, string $absoluteOutputPath, string $baseDirectory, SymfonyStyle $io): void;
}
