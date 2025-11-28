<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src\Core\Translator\IPluginTranslator;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
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
    }
}
