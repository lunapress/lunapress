<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\WpDie;

use LunaPress\FoundationContracts\Support\WpFunction\WpArray;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgs;
use LunaPress\Wp\CoreContracts\IWpError;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieFunction;

defined('ABSPATH') || exit;

final class WpDie implements IWpDieFunction
{
    private IWpError|string $message     = '';
    private int|string $title            = '';
    private int|IWpDieArgs|WpArray $args = WpArray::Empty;

    public function message(IWpError|string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function title(int|string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function args(int|IWpDieArgs|WpArray $args): self
    {
        $this->args = $args;
        return $this;
    }

    /**
     * @return array{0: IWpError|string, 1: int|string, 2: int|IWpDieArgs|array}
     */
    public function rawArgs(): array
    {
        return [
            $this->getMessage(),
            $this->getTitle(),
            $this->getArgs()
        ];
    }


    /**
     * @param array{0: IWpError|string, 1: int|string, 2: int|array} $args
     */
    public function executeWithArgs(array $args): never
    {
        wp_die(...$args);
    }

    public function getMessage(): string|IWpError
    {
        return $this->message;
    }

    public function getTitle(): string|int
    {
        return $this->title;
    }

    public function getArgs(): IWpDieArgs|WpArray
    {
        return $this->args;
    }
}
