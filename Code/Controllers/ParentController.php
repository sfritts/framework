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

    public function callMethod() {
        call_user_func(array(get_class($this), $this->methodName));
    }

    abstract protected function defaultMethod();
}
