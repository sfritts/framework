<?php

namespace Models\aTable;

class aTable extends \Models\ParentModel {

    public $id;
    public $name;
    public $timestamp;

    protected $db;

    public function __construct($id = FALSE) {
        global $db;
        $this->db = $db;

        if ($id) {
            $sql = $this->db->prepare("select * from aTable where id = ?");
            $sql->execute([$id]);
            $row = $sql->fetch(\PDO::FETCH_ASSOC);
            foreach ($row as $col => $val) {
                $this->$col = $val;
            }
        }
    }

    public function saveNew() {
        try {
            $this->id = parent::saveNew();
        } catch (\Exception $ex) {
            error_log('ERROR: ' . __CLASS__ . '->saveNew: ' . $ex->getMessage());
        }
    }

    public function update() {
        try {
            parent::update();
        } catch (\Exception $ex) {
            error_log('ERROR: ' . __CLASS__ . '->update: ' . $ex->getMessage());
        }
    }
}