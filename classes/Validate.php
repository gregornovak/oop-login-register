<?php
class Validate
{
    // declaring properties that we will use later
    private $_passed    = false,
            $_errors    = [],
            $_db        = null;
    // we get the database instance if its not already connected
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }
    // this method checks all the fields if they have been filled properly
    public function check($source, $items = [])
    {
        // goes through each input field/input type rules
        foreach($items as $item => $rules){
            // for each input/type gets the rules and the rule keys
            foreach($rules as $rule => $rule_value) {
                // trims any whitespace
                $value = trim($source[$item]);
                // if rule is required and not empty then add an error
                if($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                    // else make a switch of requirements and thieir responses
                } else if(!empty($value)){
                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} value characters");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} value characters");
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}");
                            }
                        break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, [$item, '=', $value]);
                            if($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                        break;
                        default:
                        break;
                    }
                }
            }
        }
        if(empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }
    // adds errors to errors property array
    private function addError($error)
    {
        $this->_errors[] = $error;
    }
    // gets back all errors
    public function errors()
    {
        return $this->_errors;
    }
    // checks if all checks are passed / ok
    public function passed()
    {
        return $this->_passed;
    }
}