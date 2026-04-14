<?php
declare(strict_types=1);

use LunaPress\Wp\I18n\Function\RenderContextTranslate\RenderContextTranslate;
use LunaPress\Wp\I18n\Function\RenderContextTranslate\RenderContextTranslateFactory;
use LunaPress\Wp\I18n\Function\RenderTranslate\RenderTranslate;
use LunaPress\Wp\I18n\Function\Translate\Translate;
use LunaPress\Wp\I18n\Service\Translator\Translator;
use LunaPress\Wp\I18n\Function\PluralTranslate\PluralTranslate;
use LunaPress\Wp\I18n\Function\ContextPluralTranslate\ContextPluralTranslate;
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
use LunaPress\Wp\I18nContracts\Function\LoadMuPluginTextDomain\ILoadMuPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadMuPluginTextDomain\ILoadMuPluginTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\LoadTextDomain\ILoadTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadTextDomain\ILoadTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFunction;
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

    ILoadScriptTextDomainFunction::class => autowire(LoadScriptTextDomain::class),
    ILoadScriptTextDomainFactory::class => autowire(LoadScriptTextDomainFactory::class),

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
