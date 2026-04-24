<?php

declare(strict_types=1);

namespace LunaPress\Wp\Core\Function;

use LunaPress\FoundationContracts\Support\Wp\WpCaster;
use LunaPress\FoundationContracts\Support\Wp\WpArray;
use LunaPress\FoundationContracts\Support\Wp\WpUnset;
use LunaPress\Wp\CoreContracts\DTO\WpError;
use LunaPress\Wp\CoreContracts\DTO\WpDieArgs;

final readonly class WpDie
{
    public function __construct(
        private WpCaster $caster,
    ) {
    }

    public function __invoke(
        WpError|string $message = '',
        int|string $title = '',
        WpDieArgs|WpArray $args = WpArray::Empty
    ): never
    {
        // @TODO: normalize $message
        wp_die(
            $message,
            $title,
            $this->caster->value($args, $this->mapArgs(...))
        );
    }

    private function mapArgs(WpDieArgs $args): array
    {
        $result = [];

        if ($args->response !== WpUnset::Value) {
            $result['response'] = $args->response;
        }

        if ($args->linkUrl !== WpUnset::Value) {
            $result['link_url'] = $args->linkUrl;
        }

        if ($args->linkText !== WpUnset::Value) {
            $result['link_text'] = $args->linkText;
        }

        if ($args->backLink !== WpUnset::Value) {
            $result['back_link'] = $args->backLink;
        }

        if ($args->textDirection !== WpUnset::Value) {
            $result['text_direction'] = $args->textDirection;
        }

        if ($args->charset !== WpUnset::Value) {
            $result['charset'] = $args->charset;
        }

        if ($args->code !== WpUnset::Value) {
            $result['code'] = $args->code;
        }

        if ($args->exit !== WpUnset::Value) {
            $result['exit'] = $args->exit;
        }

        return $result;
    }
}
