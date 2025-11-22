<?php
declare(strict_types=1);

use LunaPress\Wp\Core\Function\WpDie\WpDie;
use LunaPress\Wp\Core\Function\WpDie\WpDieArgs;
use LunaPress\Wp\Core\Function\WpDie\WpDieArgsFactory;
use LunaPress\Wp\Core\Function\WpDie\WpDieFactory;
use LunaPress\Wp\Core\Function\WpKses\WpKses;
use LunaPress\Wp\Core\Function\WpKses\WpKsesFactory;
use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieArgs;
use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieArgsFactory;
use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieFactory;
use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieFunction;
use LunaPress\Wp\CoreContracts\Function\WpKses\IWpKsesFactory;
use LunaPress\Wp\CoreContracts\Function\WpKses\IWpKsesFunction;
use function LunaPress\Foundation\Container\autowire;

return [
    IWpDieFunction::class => autowire(WpDie::class),
    IWpDieArgs::class => autowire(WpDieArgs::class),
    IWpDieArgsFactory::class => autowire(WpDieArgsFactory::class),
    IWpDieFactory::class => autowire(WpDieFactory::class),

    IWpKsesFunction::class => autowire(WpKses::class),
    IWpKsesFactory::class => autowire(WpKsesFactory::class),
];
