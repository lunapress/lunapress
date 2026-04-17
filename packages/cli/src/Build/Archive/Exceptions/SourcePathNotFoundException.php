<?php
declare(strict_types=1);

namespace LunaPress\Cli\Build\Archive\Exceptions;

final class SourcePathNotFoundException extends ArchiveException
{
    public static function forPath(string $path): self
    {
        return new self(sprintf('Source path "%s" does not exist.', $path));
    }
}
