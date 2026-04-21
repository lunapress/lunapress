<?php
/**
 * @var IDefaultTranslator $translator
 * @var ITranslateFactory $translateFactory
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src\Core\Translator\IDefaultTranslator;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;

/* translators: Basic */
__('basic comment', 'bred');

$config = [
    /* translators: Array title */
    'title' => __('Config Title', 'bred'),
    'desc'  => __('No comment here', 'bred'),
];

/* translators: Variable assignment */
$variable = _x('Assigned text', 'context', 'bred');

/**
 * translators: Multiline comment block
 * with some extra text.
 */
__('Multiline format', 'bred');

// translators: Single line slash format
__('Single line format', 'bred');

/*translators:No spaces format*/
__('No spaces', 'bred');

/* translators: Shared comment for multiple calls */
$combo = __('Part 1', 'bred') . ' - ' . __('Part 2', 'bred');

if (true) {
    /* translators: Inside if statement */
    echo esc_html__('Inside if', 'bred');
}

function returnTest(): string
{
    /* translators: Return statement comment */
    return __('Return text', 'bred');
}

$matchResult = match ('test') {
    /* translators: Match arm comment */
    'test' => __('Match text', 'bred'),
    default => __('Default match text', 'bred'),
};

/* translators: Ternary comment */
$ternary = true ? __('Ternary true', 'bred') : __('Ternary false', 'bred');

$closure = function() {
    /* translators: Inside closure */
    return __('Closure text', 'bred');
};

/* translators: Translator */
$translator->run(
    $translateFactory->make('translator')
);

$translator->run(
/* translators: Inside translator */
    $translateFactory->make('translator2')
);

?>

<div>
    <span>
        <?php

defined('ABSPATH') || exit;

/* translators: Inside HTML template */
        esc_html_e('HTML mixed text', 'bred');
        ?>
    </span>
</div>

<?php

defined('ABSPATH') || exit;

sprintf(
// translators: %s - sprintf
    __('%s text', 'bred'),
    'text'
);