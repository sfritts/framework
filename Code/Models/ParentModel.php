<?php

namespace Models;

/**
 *
 * @author Stewart
 */
class ParentModel {

    /**
     *
     * @var \ReflectionClass
     */
    private $reflectionClass;

    private function reflectionClassSet() {
        $this->reflectionClass = new \ReflectionClass($this);
    }

    public function saveNew() {
        $this->reflectionClassSet();
        
        $values = $this->propertiesGet();
        
        /**
         * Needed for PostGreSQL.
         *
        $namespaceName  = $this->reflectionClass->getNamespaceName();
        $namespaceArray = explode("\\", $namespaceName);
        $schema         = strtolower($namespaceArray[1]);
         *
         */
        
        $table  = strtolower($this->reflectionClass->getShortName());
        $sql    = "insert into $table (" . implode(",", $values['cols']) . ") values (" . implode(",", $values['bind']) . ")";
        
        try {
            $prep = $this->db->prepare($sql);
            $prep->execute($values['vals']);
            return $this->db->lastInsertId($schema ."." . $table."_id_seq");
            // return the id
        } catch (\Exception $ex) {
            //error_log("ERROR: ParentModel->saveNew: " . $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Gathers up PUBLIC properties from current model class and returns array
     * of their names (cols), values (vals), and for prepared statements an array
     * of '?'.
     * 
     * @param  boolean $excludeProperty Exclude the named property.
     * @return array   Array of properties and their current values.
     */
    private function propertiesGet($excludeProperty = FALSE) {
        if (!$this->reflectionClass)
            $this->reflectionClassSet();
        
        $props = $this->reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);

        $vals = [];
        $cols = [];
        $bind = [];
        $vars = [];
        foreach ($props as $prop) {
            if($excludeProperty && $prop->name === $excludeProperty){
                continue;
            }
            if (!is_null($this->{$prop->name})) {
                $vars['cols'][] = $prop->name;
                $vars['vals'][] = $this->{$prop->name};
                $vars['bind'][] = "?";
            }
        }

        return $vars;
    }

    public function update() {
        if (is_null($this->id)) {
            throw new \Exception("No id value set.");
        }

        $this->reflectionClassSet();
        
        $values = $this->propertiesGet("id");
        
        // add id to the end of value list.
        $values['vals'][] = $this->id;
        
        $sql = "update " . $this->reflectionClass->getShortName() . " set ";
        
        $x = 0;
        foreach($values['cols'] as $col){
            $x++;
            if($col === "id"){
                continue;
            }
            if($x === count($values['cols'])){
                $sql .= $col . " = ? ";
            } else {
                $sql .= $col . " = ?, ";
            }
        }
        
        $sql .= "where id = ?";
        
        $update = $this->db->prepare($sql);
        $update->execute($values['vals']);
    }

}