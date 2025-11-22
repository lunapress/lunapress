<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpEnqueueScript;

use LunaPress\FoundationContracts\Support\WpFunction\WpUnset;
use LunaPress\Wp\AssetsContracts\Enum\ScriptStrategy;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptArgs;

defined('ABSPATH') || exit;

final class WpEnqueueScriptArgs implements IWpEnqueueScriptArgs
{
    private ScriptStrategy|WpUnset $strategy = WpUnset::Value;
    private bool|WpUnset $inFooter           = WpUnset::Value;

    public function toArray(): array
    {
        return [
            'strategy' => $this->getStrategy(),
            'in_footer' => $this->getInFooter(),
        ];
    }


    public function strategy(ScriptStrategy|WpUnset $strategy): IWpEnqueueScriptArgs
    {
        $this->strategy = $strategy;
        return $this;
    }

    public function inFooter(bool|WpUnset $inFooter): IWpEnqueueScriptArgs
    {
        $this->inFooter = $inFooter;
        return $this;
    }

    public function getStrategy(): ScriptStrategy|WpUnset
    {
        return $this->strategy;
    }

    public function getInFooter(): bool|WpUnset
    {
        return $this->inFooter;
    }
}
