<?php
/**
 * Plugin Name: Test Plugin Data
 * Author: Onepix
 * License: GPLv2 or later
 *
 * @var IWCTranslator $translator
 * @var ITranslateFactory $translateFactory
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src\Core\Translator\IWCTranslator;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;

$translator->run(
    $translateFactory->make('Plugin')
);
