<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\WpDie;

use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgs;

defined('ABSPATH') || exit;

final class WpDieArgs implements IWpDieArgs
{
    private ?int $response         = null;
    private ?string $linkUrl       = null;
    private ?string $linkText      = null;
    private ?bool $backLink        = null;
    private ?string $textDirection = null;
    private ?string $charset       = null;
    private ?string $code          = null;
    private ?bool $exit            = null;

    public function toArray(): array
    {
        return [
            'response'       => $this->response,
            'link_url'       => $this->linkUrl,
            'link_text'      => $this->linkText,
            'back_link'      => $this->backLink,
            'text_direction' => $this->textDirection,
            'charset'        => $this->charset,
            'code'           => $this->code,
            'exit'           => $this->exit,
        ];
    }

    public function response(?int $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function linkUrl(?string $url): self
    {
        $this->linkUrl = $url;
        return $this;
    }

    public function linkText(?string $text): self
    {
        $this->linkText = $text;
        return $this;
    }

    public function backLink(?bool $enabled): self
    {
        $this->backLink = $enabled;
        return $this;
    }

    public function textDirection(?string $direction): self
    {
        $this->textDirection = $direction;
        return $this;
    }

    public function charset(?string $charset): self
    {
        $this->charset = $charset;
        return $this;
    }

    public function code(?string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function exit(?bool $exit): self
    {
        $this->exit = $exit;
        return $this;
    }
}
