<?php

declare(strict_types=1);

namespace LunaPress\Config\Test\Integration;

use LunaPress\Config\ConfigResolver;
use LunaPress\Config\Exceptions\InvalidConfigurationException;
use LunaPress\Config\ProjectConfig;
use LunaPress\Test\Package;

beforeEach(function () {
    $this->resolver = new ConfigResolver();
});

it('resolves config from .config directory', function () {
    $config = $this->resolver->resolve(packageFixture(Package::CONFIG, 'Case01_Default'));

    expect($config->getStraussConfig())
        ->toBeArray()
        ->toHaveKey('namespace_prefix', 'MyApp\\Vendor\\');
});

it('resolves default config when no file exists', function () {
    $config = $this->resolver->resolve(packageFixture(Package::CONFIG, 'Case02_Empty'));

    expect($config)
        ->toBeInstanceOf(ProjectConfig::class)
        ->and($config->getIgnores())->toBeEmpty()
        ->and($config->getStraussConfig())->toBeEmpty();
});

it('throws exception on invalid return type', function () {
    $this->resolver->resolve(packageFixture(Package::CONFIG, 'Case03_InvalidConfig'));
})->throws(InvalidConfigurationException::class);

it('resolves config from root directory', function () {
    $config = $this->resolver->resolve(packageFixture(Package::CONFIG, 'Case04_RootConfig'));

    expect($config->getIgnores())->toBe(['tests', '.github']);
});
