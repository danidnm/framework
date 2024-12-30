<?php

namespace ByDN\Framework\Setup;

abstract class UninstallerAbstract implements \ByDN\Framework\Setup\UninstallerInterface
{
    /**
     * @var \ByDN\Framework\App\Config
     */
    protected  $config;

    /**
     * @var \ByDN\Framework\Model\DbConnector
     */
    protected $db;

    /**
     * @param \ByDN\Framework\App\Config $config
     * @param \ByDN\Framework\Model\DbConnector $db
     */
    public function __construct(
        \ByDN\Framework\App\Config $config,
        \ByDN\Framework\Model\DbConnector $db
    ) {
        $this->config = $config;
        $this->db = $db;
    }
}
