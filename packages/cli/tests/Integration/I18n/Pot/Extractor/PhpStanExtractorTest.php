<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Integration\I18n\Pot\Extractor;

use LunaPress\Cli\I18n\Pot\Extractor\ExtractedMessage;
use LunaPress\Cli\I18n\Pot\Extractor\PhpStanExtractor;
use LunaPress\Cli\I18n\Pot\Scanner\PhpStanScanner;
use LunaPress\Test\Package;
use Symfony\Component\Finder\Finder;
use Pest\Expectation;

const PLUGIN_DOMAIN  = 'bred';
const DEFAULT_DOMAIN = 'default';

beforeEach(function () {
    $this->finder    = new Finder();
    $this->extractor = new PhpStanExtractor(
        new PhpStanScanner()
    );
});

it('phpstan extractor gets translation objects', function (string $projectPath) {
    /**
     * @var string[] $files
     */
    $files = [];

    $this->finder->in($projectPath)
        ->files()
        ->name('*.php')
        ->sortByName();

    foreach ($this->finder as $fileInfo) {
        $files[] = $fileInfo->getPathname();
    }

    $messages = $this->extractor->extract($files, $projectPath);

    // @TODO: add `references` check
    expect($messages)
        ->toBeArray()
        ->toHaveCount(24)
        ->sequence(
            // src/AllFactoryService.php
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('renderTranslateFactory');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('translateFactory');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('contextTranslateFactory')
                    ->and($message->value->getTranslation()->getContext())->toBe('context');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('pluralTranslateFactory')
                    ->and($message->value->getTranslation()->getOriginal())->toBe('pluralTranslateFactory')
                    ->and($message->value->getTranslation()->getPlural())->toBe('plurals');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('contextPluralTranslateFactory')
                    ->and($message->value->getTranslation()->getPlural())->toBe('plurals')
                    ->and($message->value->getTranslation()->getContext())->toBe('context');
            },
            // src/DefaultSubscriber.php
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(DEFAULT_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('Default text');
            },
            // src/PluginSubscriber.php
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('Text...');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('Test translator function params');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('Test all function params');
            },
            // src/WpFunctions.php
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp text');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp e_text');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_attr__');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_attr_e');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_html__');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_html_e');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp x_text')
                    ->and($message->value->getTranslation()->getContext())->toBe('x_context');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp ex_text')
                    ->and($message->value->getTranslation()->getContext())->toBe('ex_context');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_attr_x')
                    ->and($message->value->getTranslation()->getContext())->toBe('esc_attr_x_context');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_html_x')
                    ->and($message->value->getTranslation()->getContext())->toBe('esc_html_x_context');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp n_single')
                    ->and($message->value->getTranslation()->getPlural())->toBe('wp n_plural');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp nx_single')
                    ->and($message->value->getTranslation()->getPlural())->toBe('wp nx_plural')
                    ->and($message->value->getTranslation()->getContext())->toBe('nx_context');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp n_noop_single')
                    ->and($message->value->getTranslation()->getPlural())->toBe('wp n_noop_plural');
            },
            function ($message) {
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp nx_noop_single')
                    ->and($message->value->getTranslation()->getPlural())->toBe('wp nx_noop_plural')
                    ->and($message->value->getTranslation()->getContext())->toBe('nx_noop_context');
            },
            // templates/default.php
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('The template has been successfully connected');
            },
        );
})->with(packageFixtureDataset(Package::CLI, 'I18n/Pot/Extractor/PhpStanExtractor'));
