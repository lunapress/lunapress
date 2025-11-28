<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src\Core\Translator\IDefaultTranslator;
use LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src\Core\Translator\IOtherTranslator;
use LunaPress\Foundation\Subscriber\AbstractActionSubscriber;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;

final readonly class OtherSubscriber extends AbstractActionSubscriber
{
    public function __construct(
        private IOtherTranslator $translator,
        private IRenderTranslateFactory $renderTranslateFactory,
    ) {
    }

    public function __invoke(): void
    {
        $this->translator->run(
            $this->renderTranslateFactory->make('Other text')
        );
    }
}
