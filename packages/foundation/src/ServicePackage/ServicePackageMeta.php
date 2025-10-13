<?php
declare(strict_types=1);

namespace LunaPress\Foundation\ServicePackage;

use LunaPress\Foundation\PackageMeta\AbstractPackageMeta;
use LunaPress\FoundationContracts\PackageMeta\PackageType;
use LunaPress\FoundationContracts\ServicePackage\IServicePackageMeta;

defined('ABSPATH') || exit;

final readonly class ServicePackageMeta extends AbstractPackageMeta implements IServicePackageMeta
{
    public function __construct(
        string $name,
        private ?string $diPath,
    ) {
        parent::__construct($name, PackageType::SERVICE);
    }

    public function getDiPath(): ?string
    {
        return $this->diPath;
    }
}
