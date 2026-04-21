<?php

declare(strict_types=1);

namespace LunaPress\Cli\Build\Archive\Exceptions;

use function sprintf;

final class SourcePathNotFoundException extends ArchiveException
{
    public static function forPath(string $path): self
    {
        return new self(sprintf('Source path "%s" does not exist.', $path));
    }
}
