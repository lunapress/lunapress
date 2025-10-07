<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpRegisterScript;

use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptArgs;

defined('ABSPATH') || exit;

final class WpRegisterScriptArgs implements IWpRegisterScriptArgs
{
    private ?string $strategy = null;
    private ?bool $inFooter   = null;

    public function strategy(?string $strategy): self
    {
        $this->strategy = $strategy;
        return $this;
    }

    public function inFooter(?bool $inFooter): self
    {
        $this->inFooter = $inFooter;
        return $this;
    }

    public function getStrategy(): ?string
    {
        return $this->strategy;
    }

    public function isInFooter(): ?bool
    {
        return $this->inFooter;
    }

    public function toArray(): array
    {
        return [
            'strategy' => $this->strategy,
            'in_footer' => $this->inFooter,
        ];
    }
}
