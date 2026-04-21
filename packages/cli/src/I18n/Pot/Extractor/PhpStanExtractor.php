<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor;

use Generator;
use Gettext\Translation;
use LunaPress\Cli\I18n\Constants;
use LunaPress\Cli\I18n\Pot\Scanner\IScanner;
use LunaPress\Wp\I18n\Attribute\Domain;
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
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\MutatingScope;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Symfony\Component\Filesystem\Path;
use function array_merge;
use function preg_match;
use function preg_replace;
use function trim;

final readonly class PhpStanExtractor implements IExtractor
{
    use ExtractorPatternMatchTrait;
    use FormatFlagTrait;

    /**
     * @var array<string, callable(MethodCall, string): ?ExtractedMessage>
     */
    private array $handlers;

    /**
     * @var array<string, callable(FuncCall): ?ExtractedMessage>
     */
    private array $wpFunctionHandlers;

    public function __construct(
        private IScanner $scanner,
    )
    {
        $this->handlers = [
            IRenderTranslateFactory::class => $this->extractBasic(...),
            ITranslateFactory::class => $this->extractBasic(...),
            IEscHtmlTranslateFactory::class => $this->extractBasic(...),
            IEscHtmlRenderFactory::class => $this->extractBasic(...),
            IEscAttrTranslateFactory::class => $this->extractBasic(...),
            IEscAttrRenderFactory::class => $this->extractBasic(...),

            IContextTranslateFactory::class => $this->extractContext(...),
            IRenderContextTranslateFactory::class => $this->extractContext(...),
            IEscHtmlContextTranslateFactory::class => $this->extractContext(...),
            IEscAttrContextTranslateFactory::class => $this->extractContext(...),

            IPluralTranslateFactory::class => $this->extractPlural(...),
            INoopPluralTranslateFactory::class => $this->extractPlural(...),

            IContextPluralTranslateFactory::class => $this->extractContextPlural(...),
            IContextNoopPluralTranslateFactory::class => $this->extractContextNoopPlural(...),
        ];

        $this->wpFunctionHandlers = [
            '__' => $this->extractWpBasic(...),
            'translate' => $this->extractWpBasic(...),
            '_e' => $this->extractWpBasic(...),
            'esc_attr__' => $this->extractWpBasic(...),
            'esc_attr_e' => $this->extractWpBasic(...),
            'esc_html__' => $this->extractWpBasic(...),
            'esc_html_e' => $this->extractWpBasic(...),

            '_x' => $this->extractWpContext(...),
            '_ex' => $this->extractWpContext(...),
            'esc_attr_x' => $this->extractWpContext(...),
            'esc_html_x' => $this->extractWpContext(...),

            '_n' => $this->extractWpPlural(...),
            '_n_noop' => $this->extractWpNoopPlural(...),

            '_nx' => $this->extractWpContextPlural(...),
            '_nx_noop' => $this->extractWpNoopContextPlural(...),
        ];
    }

    public function getPatterns(): array
    {
        return ['*.php'];
    }

    public function extract(array $files, string $source, array $domains = [], array $ignoreDomains = []): array
    {
        /** @var ExtractedMessage[] $messages */
        $messages = [];

        /**
         * @var Comment[] $lastStmtComments
         */
        $lastStmtComments = [];
        /**
         * @var array<int, Comment[]> $structuralComments
         */
        $structuralComments = [];

        $this->scanner->scan($files, function (Node $node, MutatingScope $scope, string $file) use (&$messages, $source, &$lastStmtComments, &$structuralComments, &$currentFile): void {
            if ($currentFile !== $file) {
                $currentFile        = $file;
                $lastStmtComments   = [];
                $structuralComments = [];
            }

            if ($node instanceof Stmt) {
                $lastStmtComments = $node->getComments();
            }

            if ($node instanceof Match_) {
                foreach ($node->arms as $arm) {
                    if ($arm === null) {
						continue;
					}

					$structuralComments[$arm->body->getStartLine()] = $arm->getComments();
                }
            } elseif ($node instanceof Array_) {
                foreach ($node->items as $item) {
                    if ($item === null) {
						continue;
					}

					$structuralComments[$item->value->getStartLine()] = $item->getComments();
                }
            } elseif ($node instanceof CallLike) {
                foreach ($node->getArgs() as $arg) {
                    if (!($arg instanceof Arg)) {
						continue;
					}

					$structuralComments[$arg->value->getStartLine()] = array_merge(
						$structuralComments[$arg->value->getStartLine()] ?? [],
						$arg->getComments()
					);
                }
            }

            if ($node instanceof FuncCall) {
                $itemComments = $structuralComments[$node->getStartLine()] ?? [];
                foreach ($this->processFuncCall($node, $file, $source) as $message) {
                    $this->applyNodeComment($message, $node, $lastStmtComments, $itemComments);
                    $messages[] = $message;
                }
            } elseif ($node instanceof MethodCall) {
                $itemComments = $structuralComments[$node->getStartLine()] ?? [];
                foreach ($this->processMethodCall($node, $scope, $file, $source) as $message) {
                    $this->applyNodeComment($message, $node, $lastStmtComments, $itemComments);
                    $messages[] = $message;
                }
            }
        });

        foreach ($messages as $message) {
            $this->applyFormatFlag($message, 'php-format');
        }

        return $messages;
    }

    /**
     * @param Comment[] $stmtComments
     * @param Comment[] $itemComments
     */
    private function applyNodeComment(ExtractedMessage $message, Node $node, array $stmtComments = [], array $itemComments = []): void
    {
        $nodeComments = $node->getComments();

        if ($node instanceof CallLike) {
            foreach ($node->getArgs() as $arg) {
                if (!($arg instanceof Arg)) {
					continue;
				}

				$nodeComments = array_merge($nodeComments, $arg->getComments(), $arg->value->getComments());
            }
        }

        $comments = array_merge($nodeComments, $itemComments, $stmtComments);

        foreach ($comments as $comment) {
            $text = $comment->getText();

            if (preg_match('/(translators:\s*.*?)(?:\*\/)?$/is', $text, $matches)) {
                $cleanText = trim(preg_replace('/^[ \t]*\*[ \t]?/m', '', $matches[1]));
                $message->getTranslation()->getExtractedComments()->add($cleanText);
                return;
            }
        }
    }

    /**
     * @return Generator<ExtractedMessage>
     * @throws ShouldNotHappenException
     */
    private function processMethodCall(MethodCall $node, MutatingScope $scope, string $file, string $source): Generator
    {
        if (!$node->name instanceof Node\Identifier) {
            return;
        }

        $callerType = $scope->getType($node->var);
        if (!(new ObjectType(ITranslator::class))->isSuperTypeOf($callerType)->yes()) {
            return;
        }

        $methodName = $node->name->toString();

        if ($methodName === 'run') {
            yield from $this->processTranslatorRunCall($node, $scope, $callerType, $file, $source);
        } else {
            yield from $this->processTranslatorDirectCall($node, $methodName, $callerType, $file, $source);
        }
    }

    /**
     * @return Generator<ExtractedMessage>
     * @throws ShouldNotHappenException
     */
    private function processTranslatorRunCall(MethodCall $node, MutatingScope $scope, Type $callerType, string $file, string $source): Generator
    {
        $args = $node->getArgs();
        if (!isset($args[0]) || !$args[0] instanceof Arg) {
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

                yield from $this->yieldMessage($message, $node, $file, $source);

                return;
            }
        }
    }

    /**
     * @return Generator<ExtractedMessage>
     */
    private function processTranslatorDirectCall(MethodCall $node, string $methodName, Type $callerType, string $file, string $source): Generator
    {
        $domain = $this->resolveDomain($callerType) ?? Constants::DEFAULT_DOMAIN;

        $message = match ($methodName) {
            'translate', 'render', 'translateEscHtml', 'renderEscHtml', 'translateEscAttr', 'renderEscAttr' => $this->extractBasic($node, $domain),
            'context', 'renderContext', 'translateEscHtmlContext', 'translateEscAttrContext' => $this->extractContext($node, $domain),
            'plural', 'noopPlural' => $this->extractPlural($node, $domain),
            'contextPlural' => $this->extractContextPlural($node, $domain),
            'contextNoopPlural' => $this->extractContextNoopPlural($node, $domain),
            default => null,
        };

        yield from $this->yieldMessage($message, $node, $file, $source);
    }

    /**
     * @return Generator<ExtractedMessage>
     */
    private function processFuncCall(FuncCall $node, string $file, string $source): Generator
    {
        if (!$node->name instanceof Node\Name) {
            return;
        }

        $funcName = $node->name->toString();

        if (!isset($this->wpFunctionHandlers[$funcName])) {
            return;
        }

        /**
         * @var ?ExtractedMessage $message
         */
        $message = $this->wpFunctionHandlers[$funcName]($node);

        yield from $this->yieldMessage($message, $node, $file, $source);
    }

    /**
     * @return Generator<ExtractedMessage>
     */
    private function yieldMessage(?ExtractedMessage $message, Node $node, string $file, string $source): Generator
    {
        if ($message === null) {
			return;
		}

		$line         = $node->getStartLine();
		$relativePath = Path::makeRelative($file, $source);

		$message->getTranslation()->getReferences()->add($relativePath, $line);

		yield $message;
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

    private function extractContextNoopPlural(MethodCall $node, string $domain): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $plural   = $this->getStringArg($node, 1);
        $context  = $this->getStringArg($node, 2);

        if ($original === null) {
            return null;
        }

        $translation = Translation::create($context, $original);
        if ($plural !== null) {
            $translation->setPlural($plural);
        }

        return new ExtractedMessage($translation, $domain);
    }

    private function extractWpBasic(FuncCall $node): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $domain   = $this->getStringArg($node, 1) ?? Constants::DEFAULT_DOMAIN;

        if ($original === null) {
            return null;
        }

        return new ExtractedMessage(
            Translation::create(null, $original),
            $domain
        );
    }

    private function extractWpContext(FuncCall $node): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $context  = $this->getStringArg($node, 1);
        $domain   = $this->getStringArg($node, 2) ?? Constants::DEFAULT_DOMAIN;

        if ($original === null) {
            return null;
        }

        return new ExtractedMessage(
            Translation::create($context, $original),
            $domain
        );
    }

    private function extractWpPlural(FuncCall $node): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $plural   = $this->getStringArg($node, 1);
        $domain   = $this->getStringArg($node, 3) ?? Constants::DEFAULT_DOMAIN;

        if ($original === null) {
            return null;
        }

        $translation = Translation::create(null, $original);
        if ($plural !== null) {
            $translation->setPlural($plural);
        }

        return new ExtractedMessage($translation, $domain);
    }

    private function extractWpNoopPlural(FuncCall $node): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $plural   = $this->getStringArg($node, 1);
        $domain   = $this->getStringArg($node, 2) ?? Constants::DEFAULT_DOMAIN;

        if ($original === null) {
            return null;
        }

        $translation = Translation::create(null, $original);
        if ($plural !== null) {
            $translation->setPlural($plural);
        }

        return new ExtractedMessage($translation, $domain);
    }

    private function extractWpContextPlural(FuncCall $node): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $plural   = $this->getStringArg($node, 1);
        $context  = $this->getStringArg($node, 3);
        $domain   = $this->getStringArg($node, 4) ?? Constants::DEFAULT_DOMAIN;

        if ($original === null) {
            return null;
        }

        $translation = Translation::create($context, $original);
        if ($plural !== null) {
            $translation->setPlural($plural);
        }

        return new ExtractedMessage($translation, $domain);
    }

    private function extractWpNoopContextPlural(FuncCall $node): ?ExtractedMessage
    {
        $original = $this->getStringArg($node, 0);
        $plural   = $this->getStringArg($node, 1);
        $context  = $this->getStringArg($node, 2);
        $domain   = $this->getStringArg($node, 3) ?? Constants::DEFAULT_DOMAIN;

        if ($original === null) {
            return null;
        }

        $translation = Translation::create($context, $original);
        if ($plural !== null) {
            $translation->setPlural($plural);
        }

        return new ExtractedMessage($translation, $domain);
    }

    private function getStringArg(CallLike $node, int $index): ?string
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
