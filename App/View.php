<?php

namespace iButenko\App;

use Exception;

/**
 * - render view and give variables
 */
class View
{
    const headerFileName = VIEWS_DIR.DS.'parts'.DS.'header.php';
    const footerFileName = VIEWS_DIR.DS.'parts'.DS.'footer.php';
    
    /**
     * render
     * 
     * Renders views and creates specified variables and auth info
     * for using in views.
     * 
     * Render sequence:
     * - require header
     * - require specified view
     * - require footer
     *
     * @param  mixed $view Name of php file in Views directory
     * @param  mixed $data Associative array 'variable name' => 'variable value'
     * @return void
     */
    public static function render($view, $data = null)
    {
        $viewFileName = VIEWS_DIR.DS.$view.'.php';

        if (!file_exists($viewFileName)) {
            throw new Exception('Template file '.$viewFileName.' was not found');
        }

        // Create variables
        if (isset($data)) {
            foreach ($data as $variableName => $variableValue) {
                $$variableName = $variableValue;
            }
        }

        // Pass to view
        $auth = $_SESSION['auth'];

        require(self::headerFileName);
        require($viewFileName);
        require(self::footerFileName);
    }
}
