<?php

namespace ByDN\Framework\Setup;

class Uninstaller extends \ByDN\Framework\Setup\UninstallerAbstract implements \ByDN\Framework\Setup\UninstallerInterface
{
    public function uninstall()
    {
        // Create table if not exists
        $tableName = $this->config->getData('config/db.prefix') . \ByDN\Framework\Model\Cron\Job::TABLE_NAME;
        $this->db->query("drop table if exists {$tableName}");
    }
}
