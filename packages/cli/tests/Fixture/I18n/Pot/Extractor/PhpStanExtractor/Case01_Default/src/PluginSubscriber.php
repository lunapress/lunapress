<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src\Core\Translator\IPluginTranslator;
use LunaPress\Foundation\Subscriber\AbstractActionSubscriber;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;

final readonly class PluginSubscriber extends AbstractActionSubscriber
{
    public function __construct(
        private IPluginTranslator $translator,
        private IRenderTranslateFactory $renderTranslateFactory,
    ) {
    }

    public function __invoke(): void
    {
        $this->translator->run(
            $this->renderTranslateFactory->make('Text...')
        );
    }

    private function translatorParams(IPluginTranslator $translator): void
    {
        $translator->run(
            $this->renderTranslateFactory->make('Test translator function params')
        );
    }

    private function allParams(IPluginTranslator $translator, IRenderTranslateFactory $renderTranslateFactory): void
    {
        $translator->run(
            $renderTranslateFactory->make('Test all function params')
        );
    }
}
