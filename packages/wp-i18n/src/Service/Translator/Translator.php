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
use LunaPress\Wp\I18nContracts\Function\EscAttrContextTranslate\IEscAttrContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrRender\IEscAttrRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrTranslate\IEscAttrTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\NoopPluralTranslate\INoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;
use LunaPress\Wp\I18nContracts\Function\TranslateNoopedPlural\ITranslateNoopedPluralFactory;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;
use ReflectionClass;

defined('ABSPATH') || exit;

readonly class Translator implements ITranslator
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
