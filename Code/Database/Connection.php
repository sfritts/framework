<?php

namespace Database;

use \PDO;

/**
 * Description of Database
 *
 * @author Stewart
 */
class Connection extends \PDO {

    public $pdo;

    public function __construct() {

        $config = json_decode(file_get_contents(__DIR__ . "/.credentials.json"));

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => FALSE
        ];

        $dsn = "mysql:host=" . $config->database->host . ";dbname=" . $config->database->db_name . ";";

        try {
            return $this->pdo = parent::__construct($dsn, $config->database->username, $config->database->password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }
}
