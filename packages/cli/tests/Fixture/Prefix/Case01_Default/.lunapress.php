<?php

use LunaPress\Config\ProjectConfig;

return ProjectConfig::createDefault()
    ->withStrauss([
        'namespace_prefix' => 'MyApp\\Vendor\\',
        "classmap_prefix" => "MyAppVendor",
    ]);