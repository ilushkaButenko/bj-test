<?php

namespace iButenko\App;

use Exception;
use PHPUnit\TextUI\TestFileNotFoundException;

/**
 * - render view and give variables
 */
class View
{
    public static function render($view)
    {
        $viewFileName = VIEWS_DIR.DS.$view.'.php';

        if (file_exists($viewFileName)) {
            require($viewFileName);
        } else {
            throw new Exception('Template file '.$viewFileName.' was not found');
        }
        
    }
}
