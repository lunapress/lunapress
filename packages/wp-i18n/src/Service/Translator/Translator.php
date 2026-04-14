<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Service\Translator;

use LogicException;
use LunaPress\FoundationContracts\Support\IExecutableFunction;
use LunaPress\FoundationContracts\Support\WpFunction\IWpFunctionExecutor;
use LunaPress\Wp\I18n\Attribute\Domain;
use LunaPress\Wp\I18nContracts\Capability\IDomain;
use LunaPress\Wp\I18nContracts\Capability\IHasDomain;
use LunaPress\Wp\I18nContracts\Capability\IHasOptionalDomain;
use LunaPress\Wp\I18nContracts\Entity\INoopedPlural;
use LunaPress\Wp\I18nContracts\Entity\ITranslatorFunction;
use LunaPress\Wp\I18nContracts\Function\ContextNoopPluralTranslate\IContextNoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\DetermineLocale\IDetermineLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrContextTranslate\IEscAttrContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrRender\IEscAttrRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrTranslate\IEscAttrTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\GetAvailableLanguages\IGetAvailableLanguagesFactory;
use LunaPress\Wp\I18nContracts\Function\GetLocale\IGetLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\GetUserLocale\IGetUserLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\IsRtl\IIsRtlFactory;
use LunaPress\Wp\I18nContracts\Function\IsTextDomainLoaded\IIsTextDomainLoadedFactory;
use LunaPress\Wp\I18nContracts\Function\LoadChildThemeTextDomain\ILoadChildThemeTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadMuPluginTextDomain\ILoadMuPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadTextDomain\ILoadTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadThemeTextDomain\ILoadThemeTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\NoopPluralTranslate\INoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\NumberFormatI18n\INumberFormatI18nFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RestorePreviousLocale\IRestorePreviousLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\SwitchToLocale\ISwitchToLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;
use LunaPress\Wp\I18nContracts\Function\TranslateNoopedPlural\ITranslateNoopedPluralFactory;
use LunaPress\Wp\I18nContracts\Function\UnloadTextDomain\IUnloadTextDomainFactory;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;
use ReflectionClass;

defined('ABSPATH') || exit;

final readonly class Translator implements ITranslator
{
    public function __construct(
        private IWpFunctionExecutor $wpFunctionExecutor,
        private ITranslateFactory $translateFactory,
        private IRenderTranslateFactory $renderTranslateFactory,
        private IContextTranslateFactory $contextTranslateFactory,
        private IRenderContextTranslateFactory $renderContextTranslateFactory,
        private IPluralTranslateFactory $pluralTranslateFactory,
        private IContextPluralTranslateFactory $contextPluralTranslateFactory,
        private IEscHtmlTranslateFactory $escHtmlTranslateFactory,
        private IEscHtmlRenderFactory $escHtmlRenderFactory,
        private IEscHtmlContextTranslateFactory $escHtmlContextTranslateFactory,
        private IEscAttrTranslateFactory $escAttrTranslateFactory,
        private IEscAttrRenderFactory $escAttrRenderFactory,
        private IEscAttrContextTranslateFactory $escAttrContextTranslateFactory,
        private INoopPluralTranslateFactory $noopPluralTranslateFactory,
        private IContextNoopPluralTranslateFactory $contextNoopPluralTranslateFactory,
        private ITranslateNoopedPluralFactory $translateNoopedPluralFactory,
        private ILoadTextDomainFactory $loadTextDomainFactory,
        private IUnloadTextDomainFactory $unloadTextDomainFactory,
        private ILoadPluginTextDomainFactory $loadPluginTextDomainFactory,
        private ILoadMuPluginTextDomainFactory $loadMuPluginTextDomainFactory,
        private ILoadThemeTextDomainFactory $loadThemeTextDomainFactory,
        private ILoadChildThemeTextDomainFactory $loadChildThemeTextDomainFactory,
        private ILoadScriptTextDomainFactory $loadScriptTextDomainFactory,
        private IIsTextDomainLoadedFactory $isTextDomainLoadedFactory,
        private IGetLocaleFactory $getLocaleFactory,
        private IDetermineLocaleFactory $determineLocaleFactory,
        private IGetUserLocaleFactory $getUserLocaleFactory,
        private IGetAvailableLanguagesFactory $getAvailableLanguagesFactory,
        private IIsRtlFactory $isRtlFactory,
        private ISwitchToLocaleFactory $switchToLocaleFactory,
        private IRestorePreviousLocaleFactory $restorePreviousLocaleFactory,
        private INumberFormatI18nFactory $numberFormatI18nFactory,
    ) {
    }

    public function run(ITranslatorFunction|IExecutableFunction $function)
    {
        if ($function instanceof IHasDomain || $function instanceof IHasOptionalDomain) {
            $domain = $this->resolveDomainFromInterface();

            if ($domain !== null || $function instanceof IHasOptionalDomain) {
                $function->domain($domain);
            }
        }

        return $this->wpFunctionExecutor->execute(
            $function,
        );
    }

    public function translate(string $text): string
    {
        return $this->run(
            $this->translateFactory->make($text)
        );
    }

    public function render(string $text): void
    {
        $this->run(
            $this->renderTranslateFactory->make($text)
        );
    }

    public function context(string $text, string $context): string
    {
        return $this->run(
            $this->contextTranslateFactory->make($text, $context)
        );
    }

    public function plural(string $single, string $plural, int $number): string
    {
        return $this->run(
            $this->pluralTranslateFactory->make($single, $plural, $number)
        );
    }

    public function contextPlural(string $single, string $plural, int $number, string $context): string
    {
        return $this->run(
            $this->contextPluralTranslateFactory->make($single, $plural, $number, $context)
        );
    }

    public function renderContext(string $text, string $context): void
    {
        $this->run(
            $this->renderContextTranslateFactory->make($text, $context)
        );
    }

    public function translateEscHtml(string $text): string
    {
        return $this->run($this->escHtmlTranslateFactory->make($text));
    }

    public function renderEscHtml(string $text): void
    {
        $this->run($this->escHtmlRenderFactory->make($text));
    }

    public function translateEscHtmlContext(string $text, string $context): string
    {
        return $this->run($this->escHtmlContextTranslateFactory->make($text, $context));
    }

    public function translateEscAttr(string $text): string
    {
        return $this->run($this->escAttrTranslateFactory->make($text));
    }

    public function renderEscAttr(string $text): void
    {
        $this->run($this->escAttrRenderFactory->make($text));
    }

    public function translateEscAttrContext(string $text, string $context): string
    {
        return $this->run($this->escAttrContextTranslateFactory->make($text, $context));
    }

    public function noopPlural(string $single, string $plural): INoopedPlural
    {
        return $this->run(
            $this->noopPluralTranslateFactory->make($single, $plural)
        );
    }

    public function contextNoopPlural(string $single, string $plural, string $context): INoopedPlural
    {
        return $this->run(
            $this->contextNoopPluralTranslateFactory->make($single, $plural, $context)
        );
    }

    public function translateNoopedPlural(INoopedPlural $noopedPlural, int $number): string
    {
        return $this->run(
            $this->translateNoopedPluralFactory->make($noopedPlural, $number)
        );
    }

    public function loadTextDomain(string $moFile, ?string $locale = null): bool
    {
        return $this->run(
            $this->loadTextDomainFactory->make($this->getStrictDomain(), $moFile)->locale($locale)
        );
    }

    public function unloadTextDomain(bool $reloadable = false): bool
    {
        return $this->run(
            $this->unloadTextDomainFactory->make($this->getStrictDomain(), $reloadable)
        );
    }

    public function loadPluginTextDomain(string|false $pluginRelPath = false): bool
    {
        return $this->run(
            $this->loadPluginTextDomainFactory->make($this->getStrictDomain())->pluginRelPath($pluginRelPath)
        );
    }

    public function loadMuPluginTextDomain(string $muPluginRelPath = ''): bool
    {
        return $this->run(
            $this->loadMuPluginTextDomainFactory->make($this->getStrictDomain())->muPluginRelPath($muPluginRelPath)
        );
    }

    public function loadThemeTextDomain(string|false $path = false): bool
    {
        return $this->run(
            $this->loadThemeTextDomainFactory->make($this->getStrictDomain())->path($path)
        );
    }

    public function loadChildThemeTextDomain(string|false $path = false): bool
    {
        return $this->run(
            $this->loadChildThemeTextDomainFactory->make($this->getStrictDomain())->path($path)
        );
    }

    public function loadScriptTextDomain(string $handle, string $path): bool
    {
        return $this->run(
            $this->loadScriptTextDomainFactory->make($handle)->path($path)
        );
    }

    public function isTextDomainLoaded(): bool
    {
        return $this->run(
            $this->isTextDomainLoadedFactory->make($this->getStrictDomain())
        );
    }

    public function getLocale(): string
    {
        return $this->run(
            $this->getLocaleFactory->make()
        );
    }

    public function determineLocale(): string
    {
        return $this->run(
            $this->determineLocaleFactory->make()
        );
    }

    public function getUserLocale(int $userId = 0): string
    {
        return $this->run(
            $this->getUserLocaleFactory->make($userId)
        );
    }

    public function getAvailableLanguages(?string $dir = null): array
    {
        return $this->run(
            $this->getAvailableLanguagesFactory->make($dir)
        );
    }

    public function isRtl(): bool
    {
        return $this->run(
            $this->isRtlFactory->make()
        );
    }

    public function switchToLocale(string $locale): bool
    {
        return $this->run(
            $this->switchToLocaleFactory->make($locale)
        );
    }

    public function restorePreviousLocale(): bool
    {
        return $this->run(
            $this->restorePreviousLocaleFactory->make()
        );
    }

    public function formatNumber(float $number, int $decimals = 0): string
    {
        return $this->run(
            $this->numberFormatI18nFactory->make($number, $decimals)
        );
    }

    private function resolveDomainFromInterface(): ?string
    {
        $refClass = new ReflectionClass($this);

        foreach ($refClass->getInterfaces() as $interface) {
            $attrs = $interface->getAttributes(Domain::class);

            if (count($attrs) > 0) {
                /** @var IDomain $attrInstance */
                $attrInstance = $attrs[0]->newInstance();
                return $attrInstance->getDomain();
            }
        }

        return null;
    }

    private function getStrictDomain(): string
    {
        $domain = $this->resolveDomainFromInterface();

        if ($domain === null) {
            throw new LogicException(sprintf(
                'Cannot execute domain-specific function: Domain attribute is missing on [%s] interfaces.',
                static::class
            ));
        }

        return $domain;
    }
}
