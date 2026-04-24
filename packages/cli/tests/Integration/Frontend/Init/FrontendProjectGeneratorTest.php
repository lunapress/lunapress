<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Cli\Frontend\FrontendFramework;
use LunaPress\Cli\Frontend\Init\FrontendInitConfig;
use LunaPress\Cli\Frontend\Init\DefaultFrontendProjectGenerator;
use LunaPress\Cli\Frontend\PackageManager;
use LunaPress\Cli\Support\WorkingDirectory;
use LunaPress\Cli\Support\DefaultPathResolver;
use LunaPress\Test\Package;
use org\bovigo\vfs\vfsStream;

beforeEach(function (): void {
    $this->vfs              = vfsStream::setup();
    $this->workingDirectory = Mockery::mock(WorkingDirectory::class);
    $this->pathResolver     = new DefaultPathResolver(packagePath(Package::CLI), null, $this->workingDirectory);

    $this->workingDirectory->shouldReceive('current')->andReturn($this->vfs->url());
});

it('copies real templates into virtual filesystem', function (): void {
    $generator = new DefaultFrontendProjectGenerator($this->pathResolver);
    $generator->generate(new FrontendInitConfig(
        FrontendFramework::React,
        true,
        PackageManager::Pnpm,
        'frontend'
    ));

    $target = $this->vfs->url() . '/frontend';

    expect(file_exists($target . '/package.json'))->toBeTrue();
});
