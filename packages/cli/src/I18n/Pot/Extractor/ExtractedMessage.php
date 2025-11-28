<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor;

use Gettext\Translation;

final readonly class ExtractedMessage
{
    public function __construct(
        private Translation $translation,
        private string $domain = 'default'
    ) {
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return Translation
     */
    public function getTranslation(): Translation
    {
        return $this->translation;
    }
}
