<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScript;

use LunaPress\Wp\AssetsContracts\WpEnqueueScript\Enum\WpEnqueueScriptStrategy;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptArgs;

defined('ABSPATH') || exit;

final class WpEnqueueScriptArgs implements IWpEnqueueScriptArgs
{
    private WpEnqueueScriptStrategy $strategy;
    private bool $inFooter;

    public function toArray(): array
    {
        return [
            'strategy' => $this->getStrategy()->value,
            'in_footer' => $this->getInFooter(),
        ];
    }


    public function strategy(WpEnqueueScriptStrategy $strategy): IWpEnqueueScriptArgs
    {
        $this->strategy = $strategy;
        return $this;
    }

    public function inFooter(bool $inFooter): IWpEnqueueScriptArgs
    {
        $this->inFooter = $inFooter;
        return $this;
    }

    public function getStrategy(): WpEnqueueScriptStrategy
    {
        return $this->strategy;
    }

    public function getInFooter(): bool
    {
        return $this->inFooter;
    }
}
