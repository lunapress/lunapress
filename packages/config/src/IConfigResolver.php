<?php
declare(strict_types=1);

namespace LunaPress\Config;

use LunaPress\Config\Exceptions\InvalidConfigurationException;

defined('ABSPATH') || exit;

interface IConfigResolver
{
    /**
     * @param string $workingDirectory
     * @return ProjectConfig
     * @throws InvalidConfigurationException
     */
    public function resolve(string $workingDirectory): ProjectConfig;
}
