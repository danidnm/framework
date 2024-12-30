<?php

namespace ByDN\Framework\Model\Module;

class Module extends \ByDN\Framework\Model\AbstractDbModel
{
    const TABLE_NAME = 'modules';

    /**
     * @var string Key field in database
     */
    protected $tableName = self::TABLE_NAME;

    /**
     * @var string Key field in database
     */
    protected $idField = 'id';
}
