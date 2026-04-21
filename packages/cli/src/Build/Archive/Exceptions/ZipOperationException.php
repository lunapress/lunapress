<?php

declare(strict_types=1);

namespace LunaPress\Cli\Build\Archive\Exceptions;

use Throwable;
use function sprintf;

final class ZipOperationException extends ArchiveException
{
    public static function fromFailure(string $operation, int|string $context = '', ?Throwable $previous = null): self
    {
        $message = sprintf('Zip operation "%s" failed. Context: %s', $operation, $context);

        return new self($message, 0, $previous);
    }
}
