<?php

namespace ByDN\Framework\Model;

abstract class AbstractDbModelCollection extends AbstractDbModel
{
    protected $filters;

    public function addFieldToFilter($field, $operator, $value)
    {
        $this->filters[$field] = $value;
    }

    /**
     * @param $id
     * @return void
     */
    public function load($id)
    {
        $row = $this->db->query("SELECT * FROM {$this->tableName} WHERE {$this->idField}=%s", $id);
        $this->setData($row);
    }

    /**
     * @return void
     */
    public function save()
    {
        $fieldsToUpdate = $this->getData();
        unset($fieldsToUpdate[$this->idField]);
        $this->db->insertUpdate($this->tableName, $this->getData(), $fieldsToUpdate);
    }
}
