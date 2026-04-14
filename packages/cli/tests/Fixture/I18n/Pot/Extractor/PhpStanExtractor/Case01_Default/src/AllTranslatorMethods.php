<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src\Core\Translator\IDefaultTranslator;
use LunaPress\Foundation\Subscriber\AbstractActionSubscriber;

final readonly class AllTranslatorMethods extends AbstractActionSubscriber
{
    public function __construct(
        private IDefaultTranslator $translator,
    ) {
    }

    public function __invoke(): void
    {
        $this->translator->translate('service translate');
        $this->translator->render('service render');
        $this->translator->context('service context', 'context');
        $this->translator->plural('service plural', 'service plurals', 1);
        $this->translator->contextPlural('service context plural', 'service context plurals', 2, 'context');
        $this->translator->renderContext('service render context', 'context');
        $this->translator->translateEscHtml('service translate esc html');
        $this->translator->renderEscHtml('service render esc html');
        $this->translator->translateEscHtmlContext('service translate esc html context', 'context');
        $this->translator->translateEscAttr('service translate esc attr');
        $this->translator->renderEscAttr('service render esc attr');
        $this->translator->translateEscAttrContext('service translate esc attr context', 'context');
        $this->translator->noopPlural('service noop plural', 'service noop plurals');
        $this->translator->contextNoopPlural('service context noop plural', 'service context noop plurals', 'context');
    }
}
