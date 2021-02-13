<?php

namespace iButenko\App\Boot;

/**
 * - load project
 */

class Autoloader
{
    public static function autoload()
    {
        // Load config
        self::loadAllPhpFromDirectory(ROOT_DIR.DS.'App'.DS.'Config');

        // Load other
        foreach ([
            APP_DIR,
            CONTROLLERS_DIR,
            MODELS_DIR,
        ] as $path) {
            self::loadAllPhpFromDirectory($path);
        }
    }

    public static function isPhpFile($fileName)
    {
        return preg_match('/^.+\.php$/', $fileName) === 0 ? false : true;
    }

    public static function loadAllPhpFromDirectory($directoryPath)
    {
        $files = scandir($directoryPath);
        $directoryPath = self::endWithSlash($directoryPath);
        foreach ($files as $file) {
            if (is_file($directoryPath.$file) && self::isPhpFile($file)) {
                require_once($directoryPath.$file);
            }
        }
    }

    public static function endWithSlash($directoryPath)
    {
        if ($directoryPath[strlen($directoryPath)-1] !== DS) {
            $directoryPath .= DS;
        }
        return $directoryPath;
    }
}
