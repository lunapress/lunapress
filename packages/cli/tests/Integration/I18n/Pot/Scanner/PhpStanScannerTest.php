<?php

declare(strict_types=1);

namespace LunaPress\Cli\Test\Integration\I18n\Pot\Scanner;

use LunaPress\Cli\I18n\Pot\Scanner\PhpStanScanner;
use LunaPress\Test\Package;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Type\ObjectType;
use function expect;
use function it;

it('phpstan scanner scans files and can identify types using phpstan', function (): void {
    $projectRoot = packageFixture(Package::CLI, 'I18n/Pot/Scanner/PhpStanScanner/Case01_Default');

    $filesToScan = [
        $projectRoot . '/src/Subscriber.php',
        $projectRoot . '/src/Subscriber2.php',
    ];

    $scanner = new PhpStanScanner();

    $resolvedClasses = [];

    $scanner->scan($filesToScan, function (Node $node, MutatingScope $scope) use (&$resolvedClasses): void {
        if (!$node instanceof Class_ || !isset($node->namespacedName)) {
            return;
        }

        $className = $node->namespacedName->toString();

        $objectType  = new ObjectType($className);
        $reflection  = $objectType->getClassReflection();
        $parentClass = $reflection?->getParentClass();

        $resolvedClasses[$className] = $parentClass?->getName();
    });

    expect($resolvedClasses)->toBe([
        'LunaPress\Cli\Test\Fixture\I18n\Pot\Scanner\PhpStanScanner\Case01_Default\src\Subscriber'
        => 'LunaPress\Foundation\Subscriber\AbstractFilterSubscriber',

        'LunaPress\Cli\Test\Fixture\I18n\Pot\Scanner\PhpStanScanner\Case01_Default\src\Subscriber2'
        => null,
    ]);
});
