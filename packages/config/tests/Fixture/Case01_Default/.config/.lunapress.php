<?php

declare(strict_types=1);

use LunaPress\Config\ProjectConfig;

return ProjectConfig::createDefault()
    ->withStrauss([
        'namespace_prefix' => 'MyApp\\Vendor\\',
    ]);