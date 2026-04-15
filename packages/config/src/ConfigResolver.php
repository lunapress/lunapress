<?php
declare(strict_types=1);

namespace LunaPress\Config;

use LunaPress\Config\Exceptions\InvalidConfigurationException;

defined('ABSPATH') || exit;

final readonly class ConfigResolver
{
    private const array ALLOWED_PATHS = [
        '.config/.lunapress.php',
        '.config/lunapress.php',
        '.lunapress.php',
        'lunapress.php',
    ];

    /**
     * @param string $workingDirectory
     * @return ProjectConfig
     * @throws InvalidConfigurationException
     */
    public function resolve(string $workingDirectory): ProjectConfig
    {
        $configPath = $this->findConfigPath($workingDirectory);

        if ($configPath === null) {
            return ProjectConfig::createDefault();
        }

        return $this->loadConfig($configPath);
    }

    /**
     * @param string $basePath
     * @return string|null
     */
    private function findConfigPath(string $basePath): ?string
    {
        foreach (self::ALLOWED_PATHS as $path) {
            $fullPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;

            if (is_file($fullPath) && is_readable($fullPath)) {
                return $fullPath;
            }
        }

        return null;
    }

    /**
     * @param string $filePath
     * @return ProjectConfig
     * @throws InvalidConfigurationException
     */
    private function loadConfig(string $filePath): ProjectConfig
    {
        $config = require $filePath;

        if (!$config instanceof ProjectConfig) {
            throw new InvalidConfigurationException(
                sprintf('Configuration file "%s" must return an instance of %s.', $filePath, ProjectConfig::class)
            );
        }

        return $config;
    }
}
