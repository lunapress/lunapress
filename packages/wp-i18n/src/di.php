<?php
declare(strict_types=1);

use LunaPress\Wp\I18n\Functions\RenderTranslate\RenderTranslate;
use LunaPress\Wp\I18n\Functions\Translate\Translate;
use LunaPress\Wp\I18n\Service\Translator\Translator;
use LunaPress\Wp\I18n\Functions\PluralTranslate\PluralTranslate;
use LunaPress\Wp\I18n\Functions\ContextPluralTranslate\ContextPluralTranslate;
use LunaPress\Wp\I18n\Functions\LoadPluginTextDomain\LoadPluginTextDomain;
use LunaPress\Wp\I18n\Functions\LoadScriptTextDomain\LoadScriptTextDomain;
use LunaPress\Wp\I18n\Functions\ContextTranslate\ContextTranslate;
use LunaPress\Wp\I18n\Functions\Translate\TranslateFactory;
use LunaPress\Wp\I18n\Functions\RenderTranslate\RenderTranslateFactory;
use LunaPress\Wp\I18n\Functions\PluralTranslate\PluralTranslateFactory;
use LunaPress\Wp\I18n\Functions\ContextPluralTranslate\ContextPluralTranslateFactory;
use LunaPress\Wp\I18n\Functions\LoadPluginTextDomain\LoadPluginTextDomainFactory;
use LunaPress\Wp\I18n\Functions\LoadScriptTextDomain\LoadScriptTextDomainFactory;
use LunaPress\Wp\I18n\Functions\ContextTranslate\ContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFunction;
use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFunction;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFunction;
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

    ITranslator::class => autowire(Translator::class),
];
