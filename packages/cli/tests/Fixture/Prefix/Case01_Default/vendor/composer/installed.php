<?php return array(
    'root' => [
        'name' => 'lunapress/test-fixture',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => [],
        'dev' => true,
    ],
    'versions' => [
        'some-vendor/some-pkg' => [
            'pretty_version' => '1.0.0',
            'version' => '1.0.0.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../some-vendor/some-pkg',
            'aliases' => [],
            'reference' => 'stub-ref',
            'dev_requirement' => false,
        ],
    ],
);
