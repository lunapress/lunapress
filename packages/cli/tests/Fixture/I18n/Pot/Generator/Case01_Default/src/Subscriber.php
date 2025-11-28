<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src\Core\Translator\IPluginTranslator;
use LunaPress\Foundation\Subscriber\AbstractActionSubscriber;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;

final readonly class Subscriber extends AbstractActionSubscriber
{
    public function __construct(
        private IPluginTranslator $translator,
        private IRenderTranslateFactory $renderTranslateFactory,
    ) {
    }

    public function __invoke(): void
    {
        $this->translator->run(
            $this->renderTranslateFactory->make('Double text')
        );
    }
}
