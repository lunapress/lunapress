<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Integration\I18n\Pot\Extractor;

use LunaPress\Cli\I18n\Pot\Extractor\ExtractedMessage;
use LunaPress\Cli\I18n\Pot\Extractor\FileHeaderExtractor;
use LunaPress\Test\Package;
use Pest\Expectation;

beforeEach(function () {
    $this->extractor = new FileHeaderExtractor();
});

it('extracts plugin headers successfully', function () {
    $source = packageFixture(Package::CLI, 'I18n/Pot/Extractor/FileHeaderExtractor/Case01_Plugin');
    $files  = [$source . '/plugin.php'];

    $messages = $this->extractor->extract($files, $source);

    expect($messages)
        ->toBeArray()
        ->toHaveCount(5)
        ->sequence(
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe('plugin-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('My First Plugin')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Plugin Name of the plugin');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                expect($message->value->getDomain())->toBe('plugin-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('https://example.com/plugin')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Plugin URI of the plugin');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                expect($message->value->getDomain())->toBe('plugin-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('A test plugin')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Description of the plugin');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                expect($message->value->getDomain())->toBe('plugin-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('User One')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Author of the plugin');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                expect($message->value->getDomain())->toBe('plugin-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('https://example.com/user')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Author URI of the plugin');
            }
        );
});

it('extracts theme headers successfully', function () {
    $source = packageFixture(Package::CLI, 'I18n/Pot/Extractor/FileHeaderExtractor/Case02_Theme');
    $files  = [$source . '/style.css'];

    $messages = $this->extractor->extract($files, $source);

    expect($messages)
        ->toBeArray()
        ->toHaveCount(5)
        ->sequence(
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe('theme-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('My Simple Theme')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Theme Name of the theme');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                expect($message->value->getDomain())->toBe('theme-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('https://example.com/theme')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Theme URI of the theme');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                expect($message->value->getDomain())->toBe('theme-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('A test theme')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Description of the theme');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                expect($message->value->getDomain())->toBe('theme-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('Theme Dev')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Author of the theme');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                expect($message->value->getDomain())->toBe('theme-domain')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('https://example.com/dev')
                    ->and($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('Author URI of the theme');
            }
        );
});

it('extracts only the first plugin header found', function () {
    $source = packageFixture(Package::CLI, 'I18n/Pot/Extractor/FileHeaderExtractor/Case03_MultiplePlugins');    $files  = [
        $source . '/a-plugin.php',
        $source . '/z-plugin.php'
    ];

    $messages = $this->extractor->extract($files, $source);

    // Should only extract a-plugin.php, skipping z-plugin.php
    expect($messages)
        ->toBeArray()
        ->toHaveCount(4)
        ->sequence(
            function ($message) {
                expect($message->value->getTranslation()->getOriginal())->toBe('First Plugin');
            },
            function ($message) {
                expect($message->value->getTranslation()->getOriginal())->toBe('https://first.pl/');
            },
            function ($message) {
                expect($message->value->getTranslation()->getOriginal())->toBe('This should be extracted');
            },
            function ($message) {
                expect($message->value->getTranslation()->getOriginal())->toBe('Dev One');
            }
        );
});
