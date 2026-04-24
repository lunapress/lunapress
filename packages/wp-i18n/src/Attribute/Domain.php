<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Attribute;

use Attribute;
use LunaPress\Wp\I18nContracts\Capability\Domain as DomainContract;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Domain implements DomainContract
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
