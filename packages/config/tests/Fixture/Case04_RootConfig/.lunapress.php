<?php

declare(strict_types=1);

use LunaPress\Config\ProjectConfig;

return ProjectConfig::createDefault()
    ->withIgnores(['tests', '.github']);