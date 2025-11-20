<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpRegisterScript;

use LunaPress\FoundationContracts\Support\WpFunction\WpUnset;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\Enum\WpRegisterScriptStrategy;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptArgs;

defined('ABSPATH') || exit;

final class WpRegisterScriptArgs implements IWpRegisterScriptArgs
{
    private WpRegisterScriptStrategy|WpUnset $strategy = WpUnset::Value;
    private bool|WpUnset $inFooter                     = WpUnset::Value;

    public function strategy(WpRegisterScriptStrategy|WpUnset $strategy): self
    {
        $this->strategy = $strategy;
        return $this;
    }

    public function inFooter(bool|WpUnset $inFooter): self
    {
        $this->inFooter = $inFooter;
        return $this;
    }

    public function getStrategy(): WpRegisterScriptStrategy|WpUnset
    {
        return $this->strategy;
    }

    public function isInFooter(): bool|WpUnset
    {
        return $this->inFooter;
    }

    public function toArray(): array
    {
        return [
            'strategy' => $this->getStrategy(),
            'in_footer' => $this->isInFooter(),
        ];
    }
}
