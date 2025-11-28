<?php
/**
 * @var IPluginTranslator $translator
 * @var IRenderTranslateFactory $renderTranslate
 */
declare(strict_types=1);

use LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src\Core\Translator\IPluginTranslator;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;

?>

<div class="block">
    <?php $translator->run($renderTranslate->make('The template has been successfully connected')); ?>
</div>
