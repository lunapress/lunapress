<?php
namespace Composer;

class InstalledVersions
{
    private static $installed;

    public static function getInstalledPackages()
    {
        return array_keys(self::$installed['versions']);
    }

    public static function getInstallPath($packageName)
    {
        return isset(self::$installed['versions'][$packageName]['install_path'])
            ? self::$installed['versions'][$packageName]['install_path']
            : null;
    }

    public static function reload($data)
    {
        self::$installed = $data;
    }
}
