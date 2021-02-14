<?php

namespace iButenko\App;

use Exception;

/**
 * Validator
 * 
 * Validates input fields one by one and stores result
 * for each input separately.
 */
class Validator
{
    private $errors = [];
    private $dataType = '';
    private $data = '';
    private $inputName = '';
    
    /**
     * __construct
     *
     * @return Validator
     */
    private function __construct()
    {
        
    }
        
    /**
     * init
     *
     * Creates an instance of Validator
     * 
     * @param  mixed $data data to validate
     * @param  string $inputName name of input to structure errors
     * @return Validator
     */
    public static function init($data, $inputName = '')
    {
        return (new Validator)->newValidation($data, $inputName);
    }
    
    /**
     * newValidation
     * 
     * Provides validation of another input
     *
     * @param  mixed $data data to validate
     * @param  string $inputName name of input to structure errors
     * @return Validator
     */
    public function newValidation($data, $inputName)
    {
        $this->data = $data;
        $this->inputName = $inputName;
        $this->dataType = '';

        if (!empty($inputName)) {
            $this->errors[$inputName] = [];
        }

        return $this;
    }
    
    /**
     * getError
     *
     * @return string | boolean Returns first error message of last input
     * field (or only input field) or false
     */
    public function getError()
    {
        // if no errors
        if (empty($this->errors)) {
            return false;
        }

        // if error and input field unnamed
        if (empty($this->inputName)) {
            return $this->errors[count($this->errors) - 1];
        }

        // last error of last input field
        return $this->errors[$this->inputName][count($this->errors[$this->inputName]) - 1];
    }
    
    /**
     * getErrors
     * 
     * Get last error messages for all input names which passed
     * through the validator.
     *
     * @return array Array ['input_field_name' => 'error message']
     * or ['input_field_name' => false]
     */
    public function getErrors()
    {
        // if no named errors
        if (empty($this->errors)) {
            return false;
        }

        // Get last error for each
        $lastErrors = [];
        foreach ($this->errors as $inputName => $inputErrors) {
            if (empty($inputErrors)) {
                $lastErrors[$inputName] = false;
            } else {
                $lastErrors[$inputName] = $inputErrors[count($inputErrors) - 1];
            }
        }

        return $lastErrors;
    }
    
    /**
     * addError
     * 
     * Add error to errors array
     *
     * @param  mixed $errorMessage
     * @return void
     */
    private function addError($errorMessage)
    {
        if (empty($this->inputName)) {
            $this->errors[] = $errorMessage;
        } else {
            $this->errors[$this->inputName][] = $errorMessage;
        }
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
            $this->addError('Value must be a string');
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
            $this->addError(empty($customMessage) ? 'Invalid value' : $customMessage);
        }
        if ($result === false) {
            throw new Exception('Hey, man! Check your RegExp! That contains error: ' . $pattern);
        }
        return $this;
    }
}