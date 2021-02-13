<?php

namespace iButenko\App\Boot;

/**
 * - load project
 */

class Autoloader
{
    public static function autoload()
    {
        // find all in all directories
        // all php files in
        $files = scandir(ROOT_DIR);

    }
}
