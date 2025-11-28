<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src\Core\Translator;

use LunaPress\Wp\I18n\Attribute\Domain;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;

#[Domain('other')]
interface IOtherTranslator extends ITranslator
{
}
