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
    private $hasErrors = false;
    
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
     * hasErrors
     *
     * Chack if validator has some errors
     * 
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->hasErrors;
    }
    
    /**
     * hasNoErrors
     * 
     * Check if validator did not find errors
     *
     * @return boolean
     */
    public function hasNoErrors()
    {
        return !$this->hasErrors;
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
        $this->hasErrors = true;
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
    
    /**
     * isEmail
     * 
     * Just looks @ between any characters
     *
     * @return Validator
     */
    public function isEmail()
    {
        return $this->isMatch('/.+@.+/', 'Email address has invalid format');
    }
    
    /**
     * isNotEmptyString
     *
     * @return Validator
     */
    public function isNotEmptyString()
    {
        if ($this->data == '') {
            $this->addError('This field is required');
        }
        return $this;
    }
    
    /**
     * isNumber
     * 
     * Check data is number
     *
     * @return Validator
     */
    public function isNumber()
    {
        return $this->isMatch('/[0-9]+/', 'Value must be a number');
    }
    
    /**
     * isLessOrEqualThan
     *
     * @param  string|int $than
     * @return Validator
     */
    public function isLessOrEqualThan($than)
    {
        if ($this->data > $than) {
            $this->addError('Value must be less or equal than ' . $than);
        }
        return $this;
    }
}