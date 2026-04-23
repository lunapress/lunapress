<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Service\Translator;

use LogicException;
use LunaPress\Wp\I18n\Attribute\Domain;
use LunaPress\Wp\I18n\Function\ContextNoopPluralTranslate;
use LunaPress\Wp\I18n\Function\ContextPluralTranslate;
use LunaPress\Wp\I18n\Function\ContextTranslate;
use LunaPress\Wp\I18n\Function\EscAttrContextTranslate;
use LunaPress\Wp\I18n\Function\EscAttrRender;
use LunaPress\Wp\I18n\Function\EscAttrTranslate;
use LunaPress\Wp\I18n\Function\EscHtmlContextTranslate;
use LunaPress\Wp\I18n\Function\EscHtmlRender;
use LunaPress\Wp\I18n\Function\EscHtmlTranslate;
use LunaPress\Wp\I18n\Function\NoopPluralTranslate;
use LunaPress\Wp\I18n\Function\PluralTranslate;
use LunaPress\Wp\I18n\Function\RenderContextTranslate;
use LunaPress\Wp\I18n\Function\RenderTranslate;
use LunaPress\Wp\I18n\Function\Translate;
use LunaPress\Wp\I18n\Function\TranslateNoopedPlural;
use LunaPress\Wp\I18nContracts\Capability\IDomain;
use LunaPress\Wp\I18nContracts\Entity\INoopedPlural;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;
use ReflectionClass;
use function count;
use function sprintf;

readonly class Translator implements ITranslator
{
    private ?string $resolvedDomain;

    public function __construct(
        private Translate $translate,
        private RenderTranslate $renderTranslate,
        private ContextTranslate $contextTranslate,
        private RenderContextTranslate $renderContextTranslate,
        private PluralTranslate $pluralTranslate,
        private ContextPluralTranslate $contextPluralTranslate,
        private EscHtmlTranslate $escHtmlTranslate,
        private EscHtmlRender $escHtmlRender,
        private EscHtmlContextTranslate $escHtmlContextTranslate,
        private EscAttrTranslate $escAttrTranslate,
        private EscAttrRender $escAttrRender,
        private EscAttrContextTranslate $escAttrContextTranslate,
        private NoopPluralTranslate $noopPluralTranslate,
        private ContextNoopPluralTranslate $contextNoopPluralTranslate,
        private TranslateNoopedPlural $translateNoopedPlural,
    ) {
        $this->resolvedDomain = $this->resolveDomainFromInterface();
    }

    public function translate(string $text): string
    {
        return ($this->translate)($text, $this->getStrictDomain());
    }

    public function render(string $text): void
    {
        ($this->renderTranslate)($text, $this->getStrictDomain());
    }

    public function context(string $text, string $context): string
    {
        return ($this->contextTranslate)($text, $context, $this->getStrictDomain());
    }

    public function plural(string $single, string $plural, int $number): string
    {
        return ($this->pluralTranslate)($single, $plural, $number, $this->getStrictDomain());
    }

    public function contextPlural(string $single, string $plural, int $number, string $context): string
    {
        return ($this->contextPluralTranslate)($single, $plural, $number, $context, $this->getStrictDomain());
    }

    public function renderContext(string $text, string $context): void
    {
        ($this->renderContextTranslate)($text, $context, $this->getStrictDomain());
    }

    public function translateEscHtml(string $text): string
    {
        return ($this->escHtmlTranslate)($text, $this->getStrictDomain());
    }

    public function renderEscHtml(string $text): void
    {
        ($this->escHtmlRender)($text, $this->getStrictDomain());
    }

    public function translateEscHtmlContext(string $text, string $context): string
    {
        return ($this->escHtmlContextTranslate)($text, $context, $this->getStrictDomain());
    }

    public function translateEscAttr(string $text): string
    {
        return ($this->escAttrTranslate)($text, $this->getStrictDomain());
    }

    public function renderEscAttr(string $text): void
    {
        ($this->escAttrRender)($text, $this->getStrictDomain());
    }

    public function translateEscAttrContext(string $text, string $context): string
    {
        return ($this->escAttrContextTranslate)($text, $context, $this->getStrictDomain());
    }

    public function noopPlural(string $single, string $plural): INoopedPlural
    {
        return ($this->noopPluralTranslate)($single, $plural, $this->getStrictDomain());
    }

    public function contextNoopPlural(string $single, string $plural, string $context): INoopedPlural
    {
        return ($this->contextNoopPluralTranslate)($single, $plural, $context, $this->getStrictDomain());
    }

    public function translateNoopedPlural(INoopedPlural $noopedPlural, int $number): string
    {
        return ($this->translateNoopedPlural)($noopedPlural, $number);
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
        if ($this->resolvedDomain === null) {
            throw new LogicException(sprintf(
                'Cannot execute domain-specific function: Domain attribute is missing on [%s] interfaces.',
                static::class
            ));
        }

        return $this->resolvedDomain;
    }
}
