<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\Function\WpDie;

use LunaPress\FoundationContracts\Support\WpFunction\WpUnset;
use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieArgs;

defined('ABSPATH') || exit;

final class WpDieArgs implements IWpDieArgs
{
    private WpUnset|int $response         = WpUnset::Value;
    private WpUnset|string $linkUrl       = WpUnset::Value;
    private WpUnset|string $linkText      = WpUnset::Value;
    private WpUnset|bool $backLink        = WpUnset::Value;
    private WpUnset|string $textDirection = WpUnset::Value;
    private WpUnset|string $charset       = WpUnset::Value;
    private WpUnset|string $code          = WpUnset::Value;
    private WpUnset|bool $exit            = WpUnset::Value;

    public function toArray(): array
    {
        return [
            'response'       => $this->getResponse(),
            'link_url'       => $this->getLinkUrl(),
            'link_text'      => $this->getLinkText(),
            'back_link'      => $this->getBackLink(),
            'text_direction' => $this->getTextDirection(),
            'charset'        => $this->getCharset(),
            'code'           => $this->getCode(),
            'exit'           => $this->getExit(),
        ];
    }

    public function response(int|WpUnset $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function linkUrl(string|WpUnset $url): self
    {
        $this->linkUrl = $url;
        return $this;
    }

    public function linkText(string|WpUnset $text): self
    {
        $this->linkText = $text;
        return $this;
    }

    public function backLink(bool|WpUnset $enabled): self
    {
        $this->backLink = $enabled;
        return $this;
    }

    public function textDirection(string|WpUnset $direction): self
    {
        $this->textDirection = $direction;
        return $this;
    }

    public function charset(string|WpUnset $charset): self
    {
        $this->charset = $charset;
        return $this;
    }

    public function code(string|WpUnset $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function exit(WpUnset|bool $exit): self
    {
        $this->exit = $exit;
        return $this;
    }

    public function getResponse(): int|WpUnset
    {
        return $this->response;
    }

    public function getLinkUrl(): string|WpUnset
    {
        return $this->linkUrl;
    }

    public function getLinkText(): string|WpUnset
    {
        return $this->linkText;
    }

    public function getBackLink(): bool|WpUnset
    {
        return $this->backLink;
    }

    public function getTextDirection(): string|WpUnset
    {
        return $this->textDirection;
    }

    public function getCharset(): string|WpUnset
    {
        return $this->charset;
    }

    public function getCode(): string|WpUnset
    {
        return $this->code;
    }

    public function getExit(): bool|WpUnset
    {
        return $this->exit;
    }
}
