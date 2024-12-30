<?php

namespace ByDN\Cron\Setup;

class Installer extends \ByDN\Framework\Setup\InstallerAbstract implements \ByDN\Framework\Setup\InstallerInterface
{
    public function install()
    {

        // CRON table
        $tableName = $this->config->getData('config/db.prefix') . \ByDN\Framework\Model\Cron\Job::TABLE_NAME;
        $this->db->query("create table if not exists {$tableName} (
            id INT auto_increment,
            job_code VARCHAR(12),
            status VARCHAR(12),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            executed_at TIMESTAMP,
            finished_at TIMESTAMP,
            PRIMARY KEY (id))");
    }
}
