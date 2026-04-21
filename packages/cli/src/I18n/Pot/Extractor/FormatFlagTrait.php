<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor;

use function preg_match;

trait FormatFlagTrait
{
    private const PLACEHOLDER_REGEX = '/(?<!%)%(?:\d+\$?)?[-+0 \'"]*\d*(?:\.\d+)?[bcdeEfFgGosuxX]/';

    private function applyFormatFlag(ExtractedMessage $message, string $flag): void
    {
        $translation = $message->getTranslation();

        $hasPlaceholder = preg_match(self::PLACEHOLDER_REGEX, $translation->getOriginal()) === 1;

        if (!$hasPlaceholder && $translation->getPlural() !== null) {
            $hasPlaceholder = preg_match(self::PLACEHOLDER_REGEX, $translation->getPlural()) === 1;
        }

        if (!$hasPlaceholder) {
			return;
		}

		$translation->getFlags()->add($flag);
    }
}
