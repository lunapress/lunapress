<?php
declare(strict_types=1);

namespace LunaPress\Foundation\PackageMeta;

use LunaPress\FoundationContracts\PackageMeta\IPackageMeta;
use LunaPress\FoundationContracts\PackageMeta\PackageType;

defined('ABSPATH') || exit;

abstract readonly class AbstractPackageMeta implements IPackageMeta
{
    public function __construct(
        private string $name,
        private PackageType $type,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): PackageType
    {
        return $this->type;
    }
}
