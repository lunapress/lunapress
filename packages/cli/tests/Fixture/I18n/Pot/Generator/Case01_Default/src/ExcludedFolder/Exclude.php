<?php

declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src\ExcludedFolder;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case01_Default\src\Core\Translator\IWCTranslator;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;

class Exclude
{
    public function __construct(
        private IWCTranslator $translator,
        private ITranslateFactory $translateFactory,
    ) {
        $this->translator->run(
            $this->translateFactory->make('Ignored via folder')
        );
    }
}
