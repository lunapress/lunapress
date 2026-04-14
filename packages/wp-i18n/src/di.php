<?php
declare(strict_types=1);

use LunaPress\Wp\I18n\Function\RenderContextTranslate\RenderContextTranslate;
use LunaPress\Wp\I18n\Function\RenderContextTranslate\RenderContextTranslateFactory;
use LunaPress\Wp\I18n\Function\RenderTranslate\RenderTranslate;
use LunaPress\Wp\I18n\Function\Translate\Translate;
use LunaPress\Wp\I18n\Service\Translator\Translator;
use LunaPress\Wp\I18n\Function\PluralTranslate\PluralTranslate;
use LunaPress\Wp\I18n\Function\ContextPluralTranslate\ContextPluralTranslate;
use LunaPress\Wp\I18n\Function\LoadPluginTextDomain\LoadPluginTextDomain;
use LunaPress\Wp\I18n\Function\LoadScriptTextDomain\LoadScriptTextDomain;
use LunaPress\Wp\I18n\Function\ContextTranslate\ContextTranslate;
use LunaPress\Wp\I18n\Function\Translate\TranslateFactory;
use LunaPress\Wp\I18n\Function\RenderTranslate\RenderTranslateFactory;
use LunaPress\Wp\I18n\Function\PluralTranslate\PluralTranslateFactory;
use LunaPress\Wp\I18n\Function\ContextPluralTranslate\ContextPluralTranslateFactory;
use LunaPress\Wp\I18n\Function\LoadPluginTextDomain\LoadPluginTextDomainFactory;
use LunaPress\Wp\I18n\Function\LoadScriptTextDomain\LoadScriptTextDomainFactory;
use LunaPress\Wp\I18n\Function\ContextTranslate\ContextTranslateFactory;
use LunaPress\Wp\I18n\Function\EscHtmlContextTranslate\EscHtmlContextTranslate;
use LunaPress\Wp\I18n\Function\EscHtmlContextTranslate\EscHtmlContextTranslateFactory;
use LunaPress\Wp\I18n\Function\EscHtmlRender\EscHtmlRender;
use LunaPress\Wp\I18n\Function\EscHtmlRender\EscHtmlRenderFactory;
use LunaPress\Wp\I18n\Function\EscHtmlTranslate\EscHtmlTranslate;
use LunaPress\Wp\I18n\Function\EscHtmlTranslate\EscHtmlTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFunction;
use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFunction;
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

    ILoadScriptTextDomainFunction::class => autowire(LoadScriptTextDomain::class),
    ILoadScriptTextDomainFactory::class => autowire(LoadScriptTextDomainFactory::class),

    IContextTranslateFunction::class => autowire(ContextTranslate::class),
    IContextTranslateFactory::class => autowire(ContextTranslateFactory::class),

    IEscHtmlTranslateFunction::class => autowire(EscHtmlTranslate::class),
    IEscHtmlTranslateFactory::class => autowire(EscHtmlTranslateFactory::class),

    IEscHtmlRenderFunction::class => autowire(EscHtmlRender::class),
    IEscHtmlRenderFactory::class => autowire(EscHtmlRenderFactory::class),

    IEscHtmlContextTranslateFunction::class => autowire(EscHtmlContextTranslate::class),
    IEscHtmlContextTranslateFactory::class => autowire(EscHtmlContextTranslateFactory::class),

    IRenderContextTranslateFunction::class => autowire(RenderContextTranslate::class),
    IRenderContextTranslateFactory::class => autowire(RenderContextTranslateFactory::class),

    ITranslator::class => autowire(Translator::class),
];
