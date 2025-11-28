<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor;

use Gettext\Translation;
use LunaPress\Cli\I18n\Constants;
use LunaPress\Cli\I18n\Pot\Scanner\IScanner;
use LunaPress\Wp\I18n\Attribute\Domain;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Symfony\Component\Filesystem\Path;

final readonly class PhpStanExtractor implements IExtractor
{
    /**
     * @var array<string, callable(MethodCall, string): ?ExtractedMessage>
     */
    private array $handlers;

    public function __construct(
        private IScanner $scanner,
    ) {
        $this->handlers = [
            IRenderTranslateFactory::class => $this->extractBasic(...),
            ITranslateFactory::class       => $this->extractBasic(...),

            IContextTranslateFactory::class => $this->extractContext(...),

            IPluralTranslateFactory::class => $this->extractPlural(...),

            IContextPluralTranslateFactory::class => $this->extractContextPlural(...),
        ];
    }

    public function supports(string $filePath): bool
    {
        return str_ends_with($filePath, '.php');
    }

    public function extract(array $files, string $source): array
    {
        /** @var ExtractedMessage[] $messages */
        $messages = [];

        $this->scanner->scan($files, function (Node $node, MutatingScope $scope, string $file) use (&$messages, $source) {
            $this->processNode($node, $scope, $messages, $file, $source);
        });

        return $messages;
    }

    private function processNode(Node $node, MutatingScope $scope, array &$messages, string $file, string $source): void
    {
        if (!$node instanceof MethodCall) {
            return;
        }

        if (!$node->name instanceof Node\Identifier || $node->name->toString() !== 'run') {
            return;
        }

        $callerType = $scope->getType($node->var);
        if (!(new ObjectType(ITranslator::class))->isSuperTypeOf($callerType)->yes()) {
            return;
        }

        $args = $node->getArgs();
        if (!isset($args[0])) {
            return;
        }

        $factoryCall = $args[0]->value;
        if (!$factoryCall instanceof MethodCall || !$factoryCall->name instanceof Node\Identifier) {
            return;
        }

        if ($factoryCall->name->toString() !== 'make') {
            return;
        }

        $factoryType = $scope->getType($factoryCall->var);
        $domain      = $this->resolveDomain($callerType) ?? Constants::DEFAULT_DOMAIN;

        foreach ($this->handlers as $interface => $handler) {
            if ((new ObjectType($interface))->isSuperTypeOf($factoryType)->yes()) {
                /** @var ?ExtractedMessage $message */
                $message = $handler($factoryCall, $domain);

                if (!is_null($message)) {
                    $line         = $node->getStartLine();
                    $relativePath = Path::makeRelative($file, $source);

                    $message->getTranslation()->getReferences()->add($relativePath, $line);

                    $messages[] = $message;
                }

                return;
            }
        }
    }

    private function extractBasic(MethodCall $node, string $domain): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);

        if ($original === null) {
            return null;
        }

        return new ExtractedMessage(
            Translation::create(null, $original),
            $domain
        );
    }

    private function extractContext(MethodCall $node, string $domain): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $context  = $this->getStringArg($node, 1);

        if ($original === null) {
            return null;
        }

        return new ExtractedMessage(
            Translation::create($context, $original),
            $domain
        );
    }

    private function extractPlural(MethodCall $node, string $domain): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $plural   = $this->getStringArg($node, 1);

        if ($original === null) {
            return null;
        }

        $translation = Translation::create(null, $original);
        if ($plural !== null) {
            $translation->setPlural($plural);
        }

        return new ExtractedMessage($translation, $domain);
    }

    private function extractContextPlural(MethodCall $node, string $domain): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $plural   = $this->getStringArg($node, 1);
        $context  = $this->getStringArg($node, 3);

        if ($original === null) {
            return null;
        }

        $translation = Translation::create($context, $original);
        if ($plural !== null) {
            $translation->setPlural($plural);
        }

        return new ExtractedMessage($translation, $domain);
    }

    private function getStringArg(MethodCall $node, int $index): ?string
    {
        $args = $node->getArgs();

        if (!isset($args[$index])) {
            return null;
        }

        $valueNode = $args[$index]->value;

        if ($valueNode instanceof String_) {
            return $valueNode->value;
        }

        return null;
    }

    private function resolveDomain(Type $type): ?string
    {
        foreach ($type->getObjectClassReflections() as $reflection) {
            $attributes = $reflection->getNativeReflection()->getAttributes(Domain::class);

            if (!empty($attributes)) {
                /**
                 * @var Domain $instance
                 */
                $instance = $attributes[0]->newInstance();
                return $instance->getDomain();
            }
        }

        return null;
    }
}
