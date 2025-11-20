<?php
declare(strict_types=1);

use LunaPress\Wp\I18n\RenderTranslate\RenderTranslate;
use LunaPress\Wp\I18n\Translate\Translate;
use LunaPress\Wp\I18n\Translator\Translator;
use LunaPress\Wp\I18nContracts\RenderTranslate\IRenderTranslateFunction;
use LunaPress\Wp\I18nContracts\Translate\ITranslateFunction;
use LunaPress\Wp\I18nContracts\PluralTranslate\IPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\ContextPluralTranslate\IContextPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\LoadPluginTextDomain\ILoadPluginTextDomainFunction;
use LunaPress\Wp\I18nContracts\LoadScriptTextDomain\ILoadScriptTextDomainFunction;
use LunaPress\Wp\I18nContracts\ContextTranslate\IContextTranslateFunction;
use LunaPress\Wp\I18n\PluralTranslate\PluralTranslate;
use LunaPress\Wp\I18n\ContextPluralTranslate\ContextPluralTranslate;
use LunaPress\Wp\I18n\LoadPluginTextDomain\LoadPluginTextDomain;
use LunaPress\Wp\I18n\LoadScriptTextDomain\LoadScriptTextDomain;
use LunaPress\Wp\I18n\ContextTranslate\ContextTranslate;
use LunaPress\Wp\I18n\Translate\TranslateFactory;
use LunaPress\Wp\I18n\RenderTranslate\RenderTranslateFactory;
use LunaPress\Wp\I18n\PluralTranslate\PluralTranslateFactory;
use LunaPress\Wp\I18n\ContextPluralTranslate\ContextPluralTranslateFactory;
use LunaPress\Wp\I18n\LoadPluginTextDomain\LoadPluginTextDomainFactory;
use LunaPress\Wp\I18n\LoadScriptTextDomain\LoadScriptTextDomainFactory;
use LunaPress\Wp\I18n\ContextTranslate\ContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Translate\ITranslateFactory;
use LunaPress\Wp\I18nContracts\RenderTranslate\IRenderTranslateFactory;
use LunaPress\Wp\I18nContracts\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\LoadPluginTextDomain\ILoadPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\LoadScriptTextDomain\ILoadScriptTextDomainFactory;
use LunaPress\Wp\I18nContracts\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Translator\ITranslator;
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

    ITranslator::class => autowire(Translator::class),
];
