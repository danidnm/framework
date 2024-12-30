<?php

namespace ByDN\Framework\Model;

abstract class AbstractDbModel extends \ByDN\Framework\Model\AbstractModel
{
    /**
     * @var string Key field in database
     */
    protected $tableName;

    /**
     * @var string Key field in database
     */
    protected $idField;

    /**
     * @var array Structure of the model table
     */
    protected $tableStructure = array();

    /**
     * @var array List of fields to retrieve (all if empty)
     */
    protected $fields = array();

    /**
     * @var bool Flag to indicate if entity is loaded
     */
    protected $isLoaded = false;

    /**
     * @var array Data read from the database to manage modifications
     */
    protected $origData = [];

    /**
     * @var \ByDN\Framework\Model\DbConnector
     */
    protected $db;

    /**
     * @param DbConnector $db
     */
    public function __construct(
        \ByDN\Framework\Model\DbConnector $db
    ) {
        // Ensure the configuration is valid
        if (empty($this->tableName) || empty($this->idField)) {
            throw new \ByDN\Framework\Exception(__METHOD__ . ': DB model configuration is wrong');
        }

        // Save parameters
        $this->db = $db;
        $this->tableStructure = $this->db->getTableStructure($this->tableName);
        $this->fields = $this->db->getTableFieldNames($this->tableName);
    }

    /**
     * Loads an entity with the ID provided
     *
     * @param int $id Entity ID to retrieve
     * @param array $fields List of fields to retrieve (null or empty to load all)
     * @return $this
     */
    public function load($id, $fields = [])
    {
        $fieldsString = (!empty($fields)) ? implode(',', $fields) : '*';
        $row = $this->db->queryFirstRow("SELECT {$fieldsString} FROM {$this->tableName} WHERE {$this->idField}=%s", $id);
        $this->setData($row);
        $this->setOrigData($row);
        $this->isLoaded = true;
        return $this;
    }

    /**
     * Inserts or update the model in the database
     *
     * @return $this
     * @throws \MeekroDBException
     */
    public function save()
    {
        $isInsert = !$this->getData($this->idField);
        if ($isInsert) {
            $this->insert();
        }
        else {
            $this->update();
        }
        return $this;
    }

    /**
     * Make an insert in the DB
     *
     * @return void
     */
    private function insert()
    {
        $this->db->insert(
            $this->tableName,
            $this->getFieldsToInsert()
        );
        $this->setData($this->idField, $this->db->insertId());
    }

    /**
     * Make an update in the DB
     *
     * @return void
     * @throws \MeekroDBException
     */
    private function update()
    {
        $this->db->insertUpdate(
            $this->tableName,
            $this->getFieldsToInsert(),
            $this->getFieldsToUpdate()
        );
    }

    /**
     * Deletes current record from database
     *
     * @return $this
     */
    public function delete()
    {
        if (!$this->isLoaded) {
            throw new \ByDN\Framework\Exception(__METHOD__ . ': Entity is not loaded');
        }
        $id = $this->getData($this->idField);
        if (empty($id)) {
            throw new \ByDN\Framework\Exception(__METHOD__ . ': Entity ID unknown');
        }
        $row = $this->db->query("DELETE FROM {$this->tableName} WHERE {$this->idField}=%s", $id);
        $this->isLoaded = false;
        return $this;
    }

    /**
     * Returns a list of fields to make an insert
     *
     * @return array
     */
    protected function getFieldsToInsert()
    {
        $fieldsToInsert = array();
        foreach ($this->fields as $field) {
            if ($this->hasData($field)) {
                $fieldsToInsert[$field] = $this->getData($field);
            }
        }
        return $fieldsToInsert;
    }

    /**
     *
     * Returns a list of fields to be updated in the database
     *
     * @return array
     */
    protected function getFieldsToUpdate()
    {
        $fieldsToUpdate = array();
        foreach ($this->fields as $field) {
            $currentData = $this->getData($field);
            $origData = $this->getOrigData($field);
            if ($currentData !== $origData) {
                $fieldsToUpdate[$field] = $currentData;
            }
        }
        return $fieldsToUpdate;
    }

    /**
     * Saves original data
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setOrigData($key, $value = null)
    {
        if (is_array($key)) {
            $this->origData = $key;
            return $this;
        }
        $this->origData[$key] = $value;
        return $this;
    }

    /**
     * Returns original data from model
     *
     * @param $field
     * @return mixed
     */
    public function getOrigData($field = null)
    {
        if ($field === null) {
            return $this->origData;
        }
        return $this->origData[$field] ?? '';
    }
}
