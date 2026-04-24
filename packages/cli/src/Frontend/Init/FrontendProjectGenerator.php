<?php

declare(strict_types=1);

namespace LunaPress\Cli\Frontend\Init;

interface FrontendProjectGenerator
{
    public function generate(FrontendInitConfig $config): void;
}
