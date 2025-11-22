<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Service\Translator;

use LunaPress\FoundationContracts\Support\WpFunction\IWpFunctionExecutor;
use LunaPress\Wp\I18n\Attribute\Domain;
use LunaPress\Wp\I18nContracts\Capability\IDomain;
use LunaPress\Wp\I18nContracts\Entity\ITranslatorFunction;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;
use ReflectionClass;

defined('ABSPATH') || exit;

class Translator implements ITranslator
{
    public function __construct(
        private IWpFunctionExecutor $wpFunctionExecutor,
    ) {
    }

    public function run(ITranslatorFunction $function)
    {
        $domain = $this->resolveDomainFromInterface();

        if ($domain !== null) {
            $function->domain($domain);
        }

        return $this->wpFunctionExecutor->execute(
            $function,
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
}
