<?php

namespace Classes;

/**
 *
 * @author Stewart Fritts
 */
class ParseUri {

    //put your code here

    protected $uri;
    protected $defaultController = "main";
    // 

    /**
     * A list of URI directories you want to exclude. This makes it a little
     *   easier to move the project around. This needs to be directories listed
     *   BEFORE the name of the controller...or things will get weird.
     * 
     * @var array
     */
    protected $excludedDirectories = ["framework"];

    public function __construct() {
        // pop the leading forward slash off
        $this->uri = substr(strtok($_SERVER['REQUEST_URI'], "?"), 1);
        
        $pieces = explode("/", $this->uri);
        
        foreach($this->excludedDirectories as $directory){
            unset($pieces[array_search($directory, $pieces)]);
        }
        
        $pieces = array_values($pieces);
        /**
         * 0 = controller
         * 1 = method
         */
        $controller = "\Controllers\\" . $pieces[0];
        $method = isset($pieces[1]) ? $pieces[1] : FALSE;
        
        $loading = new $controller($pieces[0], $method);
    }

}
