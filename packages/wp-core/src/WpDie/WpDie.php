<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\WpDie;

use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgs;
use LunaPress\Wp\CoreContracts\WpError;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieFunction;

defined('ABSPATH') || exit;

final class WpDie implements IWpDieFunction
{
    private WpError|string $message;
    private int|string $title          = '';
    private int|IWpDieArgs|array $args = [];

    public function message(WpError|string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function title(int|string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function args(int|IWpDieArgs $args): self
    {
        $this->args = $args;
        return $this;
    }

    /**
     * @return array{0: WpError|string, 1: int|string, 2: int|IWpDieArgs|array}
     */
    public function rawArgs(): array
    {
        return [$this->message, $this->title, $this->args];
    }


    /**
     * @param array{0: WpError|string, 1: int|string, 2: int|array} $args
     */
    public function executeWithArgs(array $args): never
    {
        wp_die(...$args);
    }
}
