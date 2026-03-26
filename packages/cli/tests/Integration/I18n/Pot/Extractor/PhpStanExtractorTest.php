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

    expect($messages)
        ->toBeArray()
        ->toHaveCount(43)
        ->sequence(
            // src/AllFactoryService.php
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);

                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('renderTranslateFactory')
                    ->and($message->value->getTranslation()->getReferences()->toArray())->toHaveKey('src/AllFactoryService.php', [27]);
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
            // src/Comments.php
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Basic')
                ->and($message->value->getTranslation()->getReferences()->toArray())->toHaveKey('src/Comments.php', [12]);
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Array title');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toBeEmpty();
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Variable assignment');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain("translators: Multiline comment block\nwith some extra text.");
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Single line slash format');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators:No spaces format');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Shared comment for multiple calls');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Shared comment for multiple calls');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Inside if statement');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Return statement comment');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Match arm comment');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toBeEmpty();
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Ternary comment');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Ternary comment');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Inside closure');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Translator');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Inside translator');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getTranslation()->getExtractedComments()->toArray())->toContain('translators: Inside HTML template');
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
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp text');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp e_text');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_attr__');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_attr_e');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_html__');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_html_e');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp x_text')
                    ->and($message->value->getTranslation()->getContext())->toBe('x_context');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp ex_text')
                    ->and($message->value->getTranslation()->getContext())->toBe('ex_context');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_attr_x')
                    ->and($message->value->getTranslation()->getContext())->toBe('esc_attr_x_context');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp esc_html_x')
                    ->and($message->value->getTranslation()->getContext())->toBe('esc_html_x_context');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp n_single')
                    ->and($message->value->getTranslation()->getPlural())->toBe('wp n_plural');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp nx_single')
                    ->and($message->value->getTranslation()->getPlural())->toBe('wp nx_plural')
                    ->and($message->value->getTranslation()->getContext())->toBe('nx_context');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
                $message->toBeInstanceOf(ExtractedMessage::class);
                expect($message->value->getDomain())->toBe(PLUGIN_DOMAIN)
                    ->and($message->value->getTranslation()->getOriginal())->toBe('wp n_noop_single')
                    ->and($message->value->getTranslation()->getPlural())->toBe('wp n_noop_plural');
            },
            function ($message) {
                /** @var Expectation<ExtractedMessage> $message */
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
