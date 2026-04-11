<?php

declare(strict_types=1);

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
            if (str_ends_with($filePath, 'mustache/mustache/src/compat.php')) {
                return '<?php';
            }

            return $contents;
        },
    ],
];
