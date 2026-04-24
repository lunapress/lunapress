<?php

declare(strict_types=1);

namespace LunaPress\Foundation\Package;

use LunaPress\FoundationContracts\Package\Package;
use LunaPress\FoundationContracts\Plugin\Context;

abstract class AbstractPackage implements Package
{
    public function boot(): void
    {
    }

    public function activate(Context $context): void
    {
    }

    public function deactivate(Context $context): void
    {
    }
}
