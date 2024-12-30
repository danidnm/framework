<?php

namespace ByDN\Framework\Model\Cron;

class Job extends \ByDN\Framework\Model\AbstractDbModel
{
    const TABLE_NAME = 'cron_job';

    /**
     * @var string Key field in database
     */
    protected $tableName = self::TABLE_NAME;

    /**
     * @var string Key field in database
     */
    protected $idField = 'id';
}
