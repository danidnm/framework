<?php

namespace ByDN\Framework\Model;

class DbConnector extends \MeekroDB
{
    /** @var array Table structure cache */
    static array $tableStructure;

    /** @var array Table field names */
    static array $tableFieldNames;

    /**
     * Returns a list of fields with types in DB for the specified table name
     *
     * @param $tableName
     * @return array List of fields in the table
     */
    public function getTableStructure($tableName)
    {
        // If already read, return it
        if (isset(self::$tableStructure[$tableName])) {
            return self::$tableStructure[$tableName];
        }

        // Load from DB, store in cache and return
        $this->loadTableStructure($tableName);
        return self::$tableStructure[$tableName];
    }

    /**
     * Returns a list of name fields in DB for the specified table name
     *
     * @param $tableName
     * @return array List of fields in the table
     */
    public function getTableFieldNames($tableName)
    {
        // If already read, return it
        if (isset(self::$tableFieldNames[$tableName])) {
            return self::$tableFieldNames[$tableName];
        }

        // Load from DB, store in cache an return
        $this->loadTableStructure($tableName);
        return self::$tableFieldNames[$tableName];
    }

    /**
     * Loads a table structure and stores information locally
     *
     * @param $tableName
     * @return void
     */
    protected function loadTableStructure($tableName)
    {
        // Query the database
        $query = "SHOW COLUMNS FROM {$tableName}";
        $fieldsInfo = $this->query($query);

        // Store fields full info
        self::$tableStructure[$tableName] = $fieldsInfo;

        // Extract names
        $names = [];
        foreach ($fieldsInfo as $fieldInfo) {
            $names[] = $fieldInfo['Field'];
        }
        self::$tableFieldNames[$tableName] = $names;
    }
}
