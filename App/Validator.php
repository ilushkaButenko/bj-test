<?php

namespace iButenko\App;

use Exception;

class Validator
{
    private $errors = [];
    private $dataType = '';
    private $data = '';
    
    /**
     * __construct
     *
     * @param  mixed $data data to be validated
     * @return Validator
     */
    private function __construct($data)
    {
        $this->data = $data;
    }
        
    /**
     * init
     *
     * Creates an instance of Validator
     * 
     * @param  mixed $data data to validate
     * @return Validator
     */
    public static function init($data)
    {
        return new Validator($data);
    }
    
    /**
     * error
     *
     * @return string | boolean Returns first error message or false
     */
    public function error()
    {
        return empty($this->errors) ? false : $this->errors[count($this->errors) - 1];
    }
    
    /**
     * isString
     * 
     * Check that data has string type
     *
     * @return Validator
     */
    public function isString()
    {
        $this->dataType = gettype($this->data);
        if ($this->dataType !== 'string') {
            $this->errors[] = 'Value must be a string';
        }
        return $this;
    }
    
    /**
     * isMatch
     * 
     * Check if data matches regular expression
     *
     * @param  mixed $pattern
     * @param  mixed $customMessage
     * @return Validator
     */
    public function isMatch($pattern, $customMessage = '')
    {
        $result = preg_match($pattern, $this->data);
        if ($result === 0) {
            $this->errors[] = empty($customMessage) ? 'Invalid value' : $customMessage;
        }
        if ($result === false) {
            throw new Exception('Hey, man! Check your RegExp! That contains error: ' . $pattern);
        }
        return $this;
    }
}