<?php
declare(strict_types=1);

use LunaPress\Wp\Core\WpDie\WpDie;
use LunaPress\Wp\Core\WpDie\WpDieArgs;
use LunaPress\Wp\Core\WpDie\WpDieArgsFactory;
use LunaPress\Wp\Core\WpDie\WpDieBuilder;
use LunaPress\Wp\Core\WpKses\WpKses;
use LunaPress\Wp\Core\WpKses\WpKsesBuilder;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgs;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgsFactory;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieBuilder;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieFunction;
use LunaPress\Wp\CoreContracts\WpKses\IWpKsesBuilder;
use LunaPress\Wp\CoreContracts\WpKses\IWpKsesFunction;
use function LunaPress\Foundation\Container\autowire;

return [
    IWpDieFunction::class => autowire(WpDie::class),
    IWpDieArgs::class => autowire(WpDieArgs::class),
    IWpDieArgsFactory::class => autowire(WpDieArgsFactory::class),
    IWpDieBuilder::class => autowire(WpDieBuilder::class),

    IWpKsesFunction::class => autowire(WpKses::class),
    IWpKsesBuilder::class => autowire(WpKsesBuilder::class),
];
