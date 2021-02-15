<?php

namespace iButenko\App;


/**
 * Controller
 */
class Controller
{
    // Used to read arguments from request
    protected $arg;
    
    /**
     * __construct
     *
     * @param  mixed $arg argument value
     * @return void
     */
    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    protected static function filterHtmlInput($input)
    {
        $result = [];
        foreach ($input as $inputKey => $inputValue) {
            $result[$inputKey] = htmlentities($inputValue);
        }
        return $result;
    }
}
