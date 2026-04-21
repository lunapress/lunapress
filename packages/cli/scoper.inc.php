<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

return [
    'prefix' => 'LunaPressVendor',
    'exclude-namespaces' => [
        'LunaPress',
        'Symfony\Polyfill',
        'Psr\Container',
        'PHPStan',
        'PhpParser',
    ],
    'exclude-classes' => [
    ],
    'patchers' => [
        function (string $filePath, string $prefix, string $contents): string {
            if (str_ends_with($filePath, 'mustache/mustache/src/Compiler.php')) {
                $safePrefix = preg_quote($prefix, '/');

                return preg_replace(
                    '@(?<!' . $safePrefix . ')\\\\+Mustache\\\\+@',
                    '\\\\' . $prefix . '\\\\Mustache\\\\',
                    $contents
                );
            }

            if (str_ends_with($filePath, 'mustache/mustache/src/compat.php')) {
                return '<?php';
            }

            return $contents;
        },
    ],
];
