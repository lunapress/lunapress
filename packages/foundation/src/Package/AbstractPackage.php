<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Package;

use LunaPress\FoundationContracts\Package\IPackage;
use LunaPress\FoundationContracts\Plugin\IContext;

defined('ABSPATH') || exit;

abstract class AbstractPackage implements IPackage
{
    public function boot(): void
    {
    }

    public function activate(IContext $context): void
    {
    }

    public function deactivate(IContext $context): void
    {
    }
}
