<?php

declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case02_Frontend;

use LunaPress\Wp\I18n\Attribute\Domain;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;

#[Domain('bred')]
interface IPluginTranslator extends ITranslator
{
}
