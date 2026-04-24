<?php

declare(strict_types=1);

namespace LunaPress\Config;

use LunaPress\Config\Exceptions\InvalidConfigurationException;

interface ConfigResolver
{
    /**
     * @throws InvalidConfigurationException
     */
    public function resolve(string $workingDirectory): ProjectConfig;
}
