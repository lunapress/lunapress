<?php
/**
 * @var IPluginTranslator $translator
 * @var IRenderTranslateFactory $renderTranslate
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src\Core\Translator\IPluginTranslator;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;

?>

<div class="block">
    <?php
defined('ABSPATH') || exit;

$translator->run($renderTranslate->make('The template has been successfully connected'));
?>
</div>
