<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Support\Attribute;

use Attribute;
use LunaPress\Wp\I18nContracts\Support\IDomain;

defined('ABSPATH') || exit;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Domain implements IDomain
{
    public function __construct(
        public string $domain,
    ) {
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
