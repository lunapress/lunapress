<?php
declare(strict_types=1);

use LunaPress\Wp\Core\WpDie\WpDie;
use LunaPress\Wp\Core\WpDie\WpDieArgs;
use LunaPress\Wp\Core\WpDie\WpDieArgsFactory;
use LunaPress\Wp\Core\WpDie\WpDieFactory;
use LunaPress\Wp\Core\WpKses\WpKses;
use LunaPress\Wp\Core\WpKses\WpKsesFactory;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgs;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgsFactory;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieFactory;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieFunction;
use LunaPress\Wp\CoreContracts\WpKses\IWpKsesFactory;
use LunaPress\Wp\CoreContracts\WpKses\IWpKsesFunction;
use function LunaPress\Foundation\Container\autowire;

return [
    IWpDieFunction::class => autowire(WpDie::class),
    IWpDieArgs::class => autowire(WpDieArgs::class),
    IWpDieArgsFactory::class => autowire(WpDieArgsFactory::class),
    IWpDieFactory::class => autowire(WpDieFactory::class),

    IWpKsesFunction::class => autowire(WpKses::class),
    IWpKsesFactory::class => autowire(WpKsesFactory::class),
];
