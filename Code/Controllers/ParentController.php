<?php

namespace Controllers;

/**
 *
 * @author Stewart Fritts
 */
abstract class ParentController {

    protected $controllerName, $methodName = 'defaultMethod';

    public function __construct($controllerName, $methodName) {
        $this->controllerName = $controllerName;
        if ($methodName) {
            $this->methodName = $methodName;
        }
    }

    /**
     * @todo Improve error handling.
     */
    public function callMethod() {
        try{
            call_user_func(array(get_class($this), $this->methodName));
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
            die("Error Loading Page");
        }
        
    }

    abstract protected static function defaultMethod();
}
