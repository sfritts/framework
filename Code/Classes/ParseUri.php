<?php

namespace Classes;

/**
 *
 * @author Stewart Fritts <sfritts@spectrumvoip.com>
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
        
        
        var_dump($this->uri);
    }

}
