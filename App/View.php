<?php

namespace iButenko\App;

use Exception;
use PHPUnit\TextUI\TestFileNotFoundException;

/**
 * - render view and give variables
 */
class View
{
    const headerFileName = VIEWS_DIR.DS.'parts'.DS.'header.php';
    const footerFileName = VIEWS_DIR.DS.'parts'.DS.'footer.php';

    public static function render($view)
    {
        $viewFileName = VIEWS_DIR.DS.$view.'.php';

        if (!file_exists($viewFileName)) {
            throw new Exception('Template file '.$viewFileName.' was not found');
        }

        require(self::headerFileName);
        require($viewFileName);
        require(self::footerFileName);
    }
}
