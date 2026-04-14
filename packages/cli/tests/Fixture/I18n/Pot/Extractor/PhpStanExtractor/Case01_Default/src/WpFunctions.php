<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Extractor\PhpStanExtractor\Case01_Default\src;

// Basic translation functions
__('wp text', 'bred');
_e('wp e_text', 'bred');
esc_attr__('wp esc_attr__', 'bred');
esc_attr_e('wp esc_attr_e', 'bred');
esc_html__('wp esc_html__', 'bred');
esc_html_e('wp esc_html_e', 'bred');
translate('wp translate', 'bred');

// Context translation functions
_x('wp x_text', 'x_context', 'bred');
_ex('wp ex_text', 'ex_context', 'bred');
esc_attr_x('wp esc_attr_x', 'esc_attr_x_context', 'bred');
esc_html_x('wp esc_html_x', 'esc_html_x_context', 'bred');

// Plural translation functions
_n('wp n_single', 'wp n_plural', 2, 'bred');

// Context plural translation functions
_nx('wp nx_single', 'wp nx_plural', 2, 'nx_context', 'bred');

// Noop plural translation functions
_n_noop('wp n_noop_single', 'wp n_noop_plural', 'bred');

// Noop context plural translation functions
_nx_noop('wp nx_noop_single', 'wp nx_noop_plural', 'nx_noop_context', 'bred');
