<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Integration\I18n\Pot\Extractor;

use CuyZ\Valinor\MapperBuilder;
use LunaPress\Cli\I18n\Pot\Extractor\ExtractedMessage;
use LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\JavascriptExtractor;
use LunaPress\Cli\Support\ProcessFactory;
use LunaPress\Test\Package;
use Pest\Expectation;
use Symfony\Component\Finder\Finder;

beforeEach(function () {
    $this->finder    = new Finder();
    $this->extractor = new JavascriptExtractor(
        new ProcessFactory(),
        new MapperBuilder()
    );
});

it('javascript extractor gets translation objects from real files', function (string $projectPath) {
    prepareAllNestedFixtures($projectPath);

    $messages = $this->extractor->extract([], $projectPath);

    expect($messages)
        ->toBeArray()
        ->toHaveCount(4)
        ->sequence(
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe('plugin')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('text');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe('plugin')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('text with context')
                    ->and($message->value->getTranslation()->getContext())->toBe('context');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe('plugin')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('single')
                    ->and($message->value->getTranslation()->getPlural())->toBe('plural');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe('plugin')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('single2')
                    ->and($message->value->getTranslation()->getPlural())->toBe('plural2')
                    ->and($message->value->getTranslation()->getContext())->toBe('context2');
            }
        );
})->with(packageFixtureDataset(Package::CLI, 'I18n/Pot/Extractor/JavascriptExtractor'));
