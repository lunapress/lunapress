<?php
declare(strict_types=1);

use LunaPress\Cli\Frontend\FrontendFramework;
use LunaPress\Cli\Frontend\Init\FrontendInitConfig;
use LunaPress\Cli\Frontend\Init\FrontendProjectGenerator;
use LunaPress\Cli\Frontend\PackageManager;
use LunaPress\Cli\Support\IWorkingDirectory;
use LunaPress\Cli\Support\PathResolver;
use org\bovigo\vfs\vfsStream;

beforeEach(function () {
    $this->vfs              = vfsStream::setup();
    $this->workingDirectory = Mockery::mock(IWorkingDirectory::class);
    $this->pathResolver     = new PathResolver(null, $this->workingDirectory);

    $this->workingDirectory->shouldReceive('current')->andReturn($this->vfs->url());
});

it('copies real templates into virtual filesystem', function () {
    $generator = new FrontendProjectGenerator($this->pathResolver);
    $generator->generate(new FrontendInitConfig(
        FrontendFramework::React,
        true,
        PackageManager::Pnpm,
        'frontend'
    ));

    $target = $this->vfs->url() . '/frontend';

    expect(file_exists($target . '/package.json'))->toBeTrue();
});
