<?php

declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src\Core\Translator\IPluginTranslator;
use LunaPress\Wp\I18nContracts\Function\ContextNoopPluralTranslate\IContextNoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrContextTranslate\IEscAttrContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrRender\IEscAttrRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrTranslate\IEscAttrTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\NoopPluralTranslate\INoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;

final readonly class AllFactoryService
{
    public function __construct(
        private IPluginTranslator $translator,
        private ITranslateFactory $translateFactory,
        private IRenderTranslateFactory $renderTranslateFactory,
        private IContextTranslateFactory $contextTranslateFactory,
        private IPluralTranslateFactory $pluralTranslateFactory,
        private IContextPluralTranslateFactory $contextPluralTranslateFactory,
        private IRenderContextTranslateFactory $renderContextTranslateFactory,
        private IEscHtmlTranslateFactory $escHtmlTranslateFactory,
        private IEscHtmlRenderFactory $escHtmlRenderFactory,
        private IEscHtmlContextTranslateFactory $escHtmlContextTranslateFactory,
        private IEscAttrTranslateFactory $escAttrTranslateFactory,
        private IEscAttrRenderFactory $escAttrRenderFactory,
        private IEscAttrContextTranslateFactory $escAttrContextTranslateFactory,
        private INoopPluralTranslateFactory $noopPluralTranslateFactory,
        private IContextNoopPluralTranslateFactory $contextNoopPluralTranslateFactory,
    ) {
    }

    public function __invoke(): void
    {
        $this->translator->run(
            $this->renderTranslateFactory->make('renderTranslateFactory')
        );

        $this->translator->run(
            $this->translateFactory->make('translateFactory')
        );

        $this->translator->run(
            $this->contextTranslateFactory->make('contextTranslateFactory', 'context')
        );

        $this->translator->run(
            $this->pluralTranslateFactory->make('pluralTranslateFactory', 'plurals', 1)
        );

        $this->translator->run(
            $this->contextPluralTranslateFactory->make('contextPluralTranslateFactory', 'plurals', 2, 'context')
        );

        $this->translator->run(
            $this->renderContextTranslateFactory->make('renderContextTranslateFactory', 'context')
        );

        $this->translator->run(
            $this->escHtmlTranslateFactory->make('escHtmlTranslateFactory')
        );

        $this->translator->run(
            $this->escHtmlRenderFactory->make('escHtmlRenderFactory')
        );

        $this->translator->run(
            $this->escHtmlContextTranslateFactory->make('escHtmlContextTranslateFactory', 'context')
        );

        $this->translator->run(
            $this->escAttrTranslateFactory->make('escAttrTranslateFactory')
        );

        $this->translator->run(
            $this->escAttrRenderFactory->make('escAttrRenderFactory')
        );

        $this->translator->run(
            $this->escAttrContextTranslateFactory->make('escAttrContextTranslateFactory', 'context')
        );

        $this->translator->run(
            $this->noopPluralTranslateFactory->make('noopPluralTranslateFactory', 'plurals')
        );

        $this->translator->run(
            $this->contextNoopPluralTranslateFactory->make('contextNoopPluralTranslateFactory', 'plurals', 'context')
        );
    }
}
