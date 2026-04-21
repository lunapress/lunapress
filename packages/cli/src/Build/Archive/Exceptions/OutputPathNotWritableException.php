<?php

declare(strict_types=1);

namespace LunaPress\Cli\Build\Archive\Exceptions;

use function sprintf;

final class OutputPathNotWritableException extends ArchiveException
{
    public static function forPath(string $path): self
    {
        return new self(sprintf('Output path "%s" is not writable.', $path));
    }
}
