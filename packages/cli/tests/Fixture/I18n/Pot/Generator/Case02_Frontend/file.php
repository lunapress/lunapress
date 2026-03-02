<?php
/**
 * @var IDefaultTranslator $translator
 * @var ITranslateFactory $translateFactory
 */
declare(strict_types=1);

use LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case02_Frontend\IDefaultTranslator;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;

$translator->run(
    $translateFactory->make('PHP string')
);
