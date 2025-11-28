<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Scanner;

use PhpParser\Node;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\DependencyInjection\Container;
use PHPStan\DependencyInjection\ContainerFactory;
use PHPStan\DependencyInjection\MissingServiceException;
use PHPStan\Node\FileNode;
use PHPStan\Parser\ParserErrorsException;
use PHPStan\Parser\PathRoutingParser;

final class PhpStanScanner implements IScanner
{
    private Container $container;

    private static ?Container $sharedContainer = null;

    public function __construct()
    {
        if (self::$sharedContainer === null) {
            $containerFactory      = new ContainerFactory(getcwd());
            self::$sharedContainer = $containerFactory->create(
                tempDirectory: sys_get_temp_dir(),
                additionalConfigFiles: [],
                analysedPaths: [],
            );
        }

        $this->container = self::$sharedContainer;
    }

    /**
     * @throws ParserErrorsException
     * @throws MissingServiceException
     */
    public function scan(array $files, callable $callback): void
    {
        /**
         * @var PathRoutingParser $parser
         */
        $parser       = $this->container->getService('pathRoutingParser');
        $resolver     = $this->container->getByType(NodeScopeResolver::class);
        $scopeFactory = $this->container->getByType(ScopeFactory::class);

        $parser->setAnalysedFiles($files);

        foreach ($files as $file) {
            $stmts = $parser->parseFile($file);

            $nodeCallback = function (Node $node, MutatingScope $scope) use ($callback, $file): void {
                $callback($node, $scope, $file);
            };

            $scope = $scopeFactory->create(ScopeContext::create($file), $nodeCallback);

            $nodeCallback(new FileNode($stmts), $scope);

            $resolver->processNodes($stmts, $scope, $nodeCallback);
        }
    }
}
