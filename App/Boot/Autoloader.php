<?php

namespace iButenko\App\Boot;

/**
 * Autoloader
 * 
 * Custom autoloader for project.
 */
class Autoloader
{    
    /**
     * autoload
     * 
     * Loads all needle files.
     *
     * @return void
     */
    public static function autoload()
    {
        // Load config
        self::loadAllPhpFromDirectory(ROOT_DIR.DS.'App'.DS.'Config');

        // Load other
        foreach ([
            APP_DIR,
            CONTROLLERS_DIR,
            MODELS_DIR,
            HELPER_DIR,
        ] as $path) {
            self::loadAllPhpFromDirectory($path);
        }
    }
    
    /**
     * isPhpFile
     * 
     * Check by filename.
     *
     * @param  mixed $fileName
     * @return void
     */
    public static function isPhpFile($fileName)
    {
        return preg_match('/^.+\.php$/', $fileName) === 0 ? false : true;
    }
    
    /**
     * loadAllPhpFromDirectory
     * 
     * Requires php files from directory. 
     *
     * @param  string $directoryPath
     * @return void
     */
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
    
    /**
     * endWithSlash
     *
     * @param  mixed $directoryPath
     * @return void
     */
    public static function endWithSlash($directoryPath)
    {
        if ($directoryPath[strlen($directoryPath)-1] !== DS) {
            $directoryPath .= DS;
        }
        return $directoryPath;
    }
}
