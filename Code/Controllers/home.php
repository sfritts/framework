<?php

namespace Controllers;

/**
 * Description of home
 *
 * @author Stewart Fritts
 */
class home extends ParentController {
    //put your code here
    public function __construct($controllerName, $method) {
        parent::__construct($controllerName, $method);
        
        parent::callMethod();
    }
    
    public function defaultMethod() {
        
        $loader = new \Twig\Loader\FilesystemLoader(["Code/Templates", "Code/Templates/home"]);
        $twig = new \Twig\Environment($loader);
        
        echo $twig->render("home.html.twig", []);
    }
}
