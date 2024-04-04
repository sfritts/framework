<?php

require_once "Code/autoloader.php";
require_once "vendor/autoload.php";


$parser = new \Classes\ParseUri();

try {
    $db = new \Database\Connection();
} catch (Exception $ex) {
    error_log("Failed to connect to database on: " . __FILE__ . ", at: " . __LINE__);
}