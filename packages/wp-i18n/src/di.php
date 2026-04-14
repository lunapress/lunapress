<?php
declare(strict_types=1);

use LunaPress\Wp\I18n\Function\RenderContextTranslate\RenderContextTranslate;
use LunaPress\Wp\I18n\Function\RenderContextTranslate\RenderContextTranslateFactory;
use LunaPress\Wp\I18n\Function\RenderTranslate\RenderTranslate;
use LunaPress\Wp\I18n\Function\Translate\Translate;
use LunaPress\Wp\I18n\Service\Translator\Translator;
use LunaPress\Wp\I18n\Function\PluralTranslate\PluralTranslate;
use LunaPress\Wp\I18n\Function\ContextPluralTranslate\ContextPluralTranslate;
use LunaPress\Wp\I18n\Function\IsTextDomainLoaded\IsTextDomainLoaded;
use LunaPress\Wp\I18n\Function\IsTextDomainLoaded\IsTextDomainLoadedFactory;
use LunaPress\Wp\I18n\Function\RestorePreviousLocale\RestorePreviousLocale;
use LunaPress\Wp\I18n\Function\RestorePreviousLocale\RestorePreviousLocaleFactory;
use LunaPress\Wp\I18n\Function\SwitchToLocale\SwitchToLocale;
use LunaPress\Wp\I18n\Function\SwitchToLocale\SwitchToLocaleFactory;
use LunaPress\Wp\I18n\Function\GetUserLocale\GetUserLocale;
use LunaPress\Wp\I18n\Function\GetUserLocale\GetUserLocaleFactory;
use LunaPress\Wp\I18n\Function\GetAvailableLanguages\GetAvailableLanguages;
use LunaPress\Wp\I18n\Function\GetAvailableLanguages\GetAvailableLanguagesFactory;
use LunaPress\Wp\I18n\Function\IsRtl\IsRtl;
use LunaPress\Wp\I18n\Function\IsRtl\IsRtlFactory;
use LunaPress\Wp\I18n\Function\DetermineLocale\DetermineLocale;
use LunaPress\Wp\I18n\Function\DetermineLocale\DetermineLocaleFactory;
use LunaPress\Wp\I18n\Function\GetLocale\GetLocale;
use LunaPress\Wp\I18n\Function\GetLocale\GetLocaleFactory;
use LunaPress\Wp\I18n\Function\LoadChildThemeTextDomain\LoadChildThemeTextDomain;
use LunaPress\Wp\I18n\Function\LoadChildThemeTextDomain\LoadChildThemeTextDomainFactory;
use LunaPress\Wp\I18n\Function\LoadMuPluginTextDomain\LoadMuPluginTextDomain;
use LunaPress\Wp\I18n\Function\LoadMuPluginTextDomain\LoadMuPluginTextDomainFactory;
use LunaPress\Wp\I18n\Function\LoadPluginTextDomain\LoadPluginTextDomain;
use LunaPress\Wp\I18n\Function\LoadScriptTextDomain\LoadScriptTextDomain;
use LunaPress\Wp\I18n\Function\ContextTranslate\ContextTranslate;
use LunaPress\Wp\I18n\Function\Translate\TranslateFactory;
use LunaPress\Wp\I18n\Function\RenderTranslate\RenderTranslateFactory;
use LunaPress\Wp\I18n\Function\PluralTranslate\PluralTranslateFactory;
use LunaPress\Wp\I18n\Function\ContextPluralTranslate\ContextPluralTranslateFactory;
use LunaPress\Wp\I18n\Function\LoadTextDomain\LoadTextDomain;
use LunaPress\Wp\I18n\Function\LoadTextDomain\LoadTextDomainFactory;
use LunaPress\Wp\I18n\Function\ContextNoopPluralTranslate\ContextNoopPluralTranslate;
use LunaPress\Wp\I18n\Function\ContextNoopPluralTranslate\ContextNoopPluralTranslateFactory;
use LunaPress\Wp\I18n\Function\EscAttrContextTranslate\EscAttrContextTranslate;
use LunaPress\Wp\I18n\Function\EscAttrContextTranslate\EscAttrContextTranslateFactory;
use LunaPress\Wp\I18n\Function\EscAttrRender\EscAttrRender;
use LunaPress\Wp\I18n\Function\EscAttrRender\EscAttrRenderFactory;
use LunaPress\Wp\I18n\Function\EscAttrTranslate\EscAttrTranslate;
use LunaPress\Wp\I18n\Function\EscAttrTranslate\EscAttrTranslateFactory;
use LunaPress\Wp\I18n\Function\LoadPluginTextDomain\LoadPluginTextDomainFactory;
use LunaPress\Wp\I18n\Function\LoadScriptTextDomain\LoadScriptTextDomainFactory;
use LunaPress\Wp\I18n\Function\LoadThemeTextDomain\LoadThemeTextDomain;
use LunaPress\Wp\I18n\Function\LoadThemeTextDomain\LoadThemeTextDomainFactory;
use LunaPress\Wp\I18n\Function\ContextTranslate\ContextTranslateFactory;
use LunaPress\Wp\I18n\Function\NoopPluralTranslate\NoopPluralTranslate;
use LunaPress\Wp\I18n\Function\NoopPluralTranslate\NoopPluralTranslateFactory;
use LunaPress\Wp\I18n\Function\EscHtmlContextTranslate\EscHtmlContextTranslate;
use LunaPress\Wp\I18n\Function\EscHtmlContextTranslate\EscHtmlContextTranslateFactory;
use LunaPress\Wp\I18n\Function\EscHtmlRender\EscHtmlRender;
use LunaPress\Wp\I18n\Function\EscHtmlRender\EscHtmlRenderFactory;
use LunaPress\Wp\I18n\Function\EscHtmlTranslate\EscHtmlTranslate;
use LunaPress\Wp\I18n\Function\EscHtmlTranslate\EscHtmlTranslateFactory;
use LunaPress\Wp\I18n\Function\TranslateNoopedPlural\TranslateNoopedPlural;
use LunaPress\Wp\I18n\Function\TranslateNoopedPlural\TranslateNoopedPluralFactory;
use LunaPress\Wp\I18n\Function\UnloadTextDomain\UnloadTextDomain;
use LunaPress\Wp\I18n\Function\UnloadTextDomain\UnloadTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\ContextNoopPluralTranslate\IContextNoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextNoopPluralTranslate\IContextNoopPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\EscAttrTranslate\IEscAttrTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrTranslate\IEscAttrTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\EscAttrRender\IEscAttrRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrRender\IEscAttrRenderFunction;
use LunaPress\Wp\I18nContracts\Function\EscAttrContextTranslate\IEscAttrContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrContextTranslate\IEscAttrContextTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFunction;
use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\LoadChildThemeTextDomain\ILoadChildThemeTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadChildThemeTextDomain\ILoadChildThemeTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\GetLocale\IGetLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\GetLocale\IGetLocaleFunction;
use LunaPress\Wp\I18nContracts\Function\SwitchToLocale\ISwitchToLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\SwitchToLocale\ISwitchToLocaleFunction;
use LunaPress\Wp\I18nContracts\Function\RestorePreviousLocale\IRestorePreviousLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\RestorePreviousLocale\IRestorePreviousLocaleFunction;
use LunaPress\Wp\I18nContracts\Function\GetUserLocale\IGetUserLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\GetUserLocale\IGetUserLocaleFunction;
use LunaPress\Wp\I18nContracts\Function\GetAvailableLanguages\IGetAvailableLanguagesFactory;
use LunaPress\Wp\I18nContracts\Function\GetAvailableLanguages\IGetAvailableLanguagesFunction;
use LunaPress\Wp\I18nContracts\Function\DetermineLocale\IDetermineLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\DetermineLocale\IDetermineLocaleFunction;
use LunaPress\Wp\I18nContracts\Function\IsRtl\IIsRtlFactory;
use LunaPress\Wp\I18nContracts\Function\IsRtl\IIsRtlFunction;
use LunaPress\Wp\I18nContracts\Function\IsTextDomainLoaded\IIsTextDomainLoadedFactory;
use LunaPress\Wp\I18nContracts\Function\IsTextDomainLoaded\IIsTextDomainLoadedFunction;
use LunaPress\Wp\I18nContracts\Function\LoadMuPluginTextDomain\ILoadMuPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadMuPluginTextDomain\ILoadMuPluginTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\LoadTextDomain\ILoadTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadTextDomain\ILoadTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\LoadThemeTextDomain\ILoadThemeTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadThemeTextDomain\ILoadThemeTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\NoopPluralTranslate\INoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\NoopPluralTranslate\INoopPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFunction;
use LunaPress\Wp\I18nContracts\Function\TranslateNoopedPlural\ITranslateNoopedPluralFactory;
use LunaPress\Wp\I18nContracts\Function\TranslateNoopedPlural\ITranslateNoopedPluralFunction;
use LunaPress\Wp\I18nContracts\Function\UnloadTextDomain\IUnloadTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\UnloadTextDomain\IUnloadTextDomainFunction;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;
use function LunaPress\Foundation\Container\autowire;

return [
    ITranslateFunction::class => autowire(Translate::class),
    ITranslateFactory::class => autowire(TranslateFactory::class),

    IRenderTranslateFunction::class => autowire(RenderTranslate::class),
    IRenderTranslateFactory::class => autowire(RenderTranslateFactory::class),

    IPluralTranslateFunction::class => autowire(PluralTranslate::class),
    IPluralTranslateFactory::class => autowire(PluralTranslateFactory::class),

    IContextPluralTranslateFunction::class => autowire(ContextPluralTranslate::class),
    IContextPluralTranslateFactory::class => autowire(ContextPluralTranslateFactory::class),

    ILoadPluginTextDomainFunction::class => autowire(LoadPluginTextDomain::class),
    ILoadPluginTextDomainFactory::class => autowire(LoadPluginTextDomainFactory::class),

    ILoadMuPluginTextDomainFunction::class => autowire(LoadMuPluginTextDomain::class),
    ILoadMuPluginTextDomainFactory::class => autowire(LoadMuPluginTextDomainFactory::class),

    ILoadChildThemeTextDomainFunction::class => autowire(LoadChildThemeTextDomain::class),
    ILoadChildThemeTextDomainFactory::class => autowire(LoadChildThemeTextDomainFactory::class),

    IIsTextDomainLoadedFunction::class => autowire(IsTextDomainLoaded::class),
    IIsTextDomainLoadedFactory::class => autowire(IsTextDomainLoadedFactory::class),

    IGetLocaleFunction::class => autowire(GetLocale::class),
    IGetLocaleFactory::class => autowire(GetLocaleFactory::class),

    IGetUserLocaleFunction::class => autowire(GetUserLocale::class),
    IGetUserLocaleFactory::class => autowire(GetUserLocaleFactory::class),

    ISwitchToLocaleFunction::class => autowire(SwitchToLocale::class),
    ISwitchToLocaleFactory::class => autowire(SwitchToLocaleFactory::class),

    IRestorePreviousLocaleFunction::class => autowire(RestorePreviousLocale::class),
    IRestorePreviousLocaleFactory::class => autowire(RestorePreviousLocaleFactory::class),

    IGetAvailableLanguagesFunction::class => autowire(GetAvailableLanguages::class),
    IGetAvailableLanguagesFactory::class => autowire(GetAvailableLanguagesFactory::class),

    IDetermineLocaleFunction::class => autowire(DetermineLocale::class),
    IDetermineLocaleFactory::class => autowire(DetermineLocaleFactory::class),

    IIsRtlFunction::class => autowire(IsRtl::class),
    IIsRtlFactory::class => autowire(IsRtlFactory::class),

    ILoadScriptTextDomainFunction::class => autowire(LoadScriptTextDomain::class),
    ILoadScriptTextDomainFactory::class => autowire(LoadScriptTextDomainFactory::class),

    ILoadThemeTextDomainFunction::class => autowire(LoadThemeTextDomain::class),
    ILoadThemeTextDomainFactory::class => autowire(LoadThemeTextDomainFactory::class),

    IContextTranslateFunction::class => autowire(ContextTranslate::class),
    IContextTranslateFactory::class => autowire(ContextTranslateFactory::class),

    IEscHtmlTranslateFunction::class => autowire(EscHtmlTranslate::class),
    IEscHtmlTranslateFactory::class => autowire(EscHtmlTranslateFactory::class),

    IEscAttrTranslateFunction::class => autowire(EscAttrTranslate::class),
    IEscAttrTranslateFactory::class => autowire(EscAttrTranslateFactory::class),

    IEscAttrRenderFunction::class => autowire(EscAttrRender::class),
    IEscAttrRenderFactory::class => autowire(EscAttrRenderFactory::class),

    IEscAttrContextTranslateFunction::class => autowire(EscAttrContextTranslate::class),
    IEscAttrContextTranslateFactory::class => autowire(EscAttrContextTranslateFactory::class),

    IEscHtmlRenderFunction::class => autowire(EscHtmlRender::class),
    IEscHtmlRenderFactory::class => autowire(EscHtmlRenderFactory::class),

    IEscHtmlContextTranslateFunction::class => autowire(EscHtmlContextTranslate::class),
    IEscHtmlContextTranslateFactory::class => autowire(EscHtmlContextTranslateFactory::class),

    IRenderContextTranslateFunction::class => autowire(RenderContextTranslate::class),
    IRenderContextTranslateFactory::class => autowire(RenderContextTranslateFactory::class),

    INoopPluralTranslateFunction::class => autowire(NoopPluralTranslate::class),
    INoopPluralTranslateFactory::class => autowire(NoopPluralTranslateFactory::class),

    IContextNoopPluralTranslateFunction::class => autowire(ContextNoopPluralTranslate::class),
    IContextNoopPluralTranslateFactory::class => autowire(ContextNoopPluralTranslateFactory::class),

    ILoadTextDomainFunction::class => autowire(LoadTextDomain::class),
    ILoadTextDomainFactory::class => autowire(LoadTextDomainFactory::class),

    IUnloadTextDomainFunction::class => autowire(UnloadTextDomain::class),
    IUnloadTextDomainFactory::class => autowire(UnloadTextDomainFactory::class),

    ITranslateNoopedPluralFunction::class => autowire(TranslateNoopedPlural::class),
    ITranslateNoopedPluralFactory::class => autowire(TranslateNoopedPluralFactory::class),

    ITranslator::class => autowire(Translator::class),
];
