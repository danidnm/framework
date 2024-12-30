<?php

namespace ByDN\Framework\App;

use ByDN\Framework\Setup\Installer;

abstract class AppAbstract implements AppInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private \Psr\Container\ContainerInterface $objectManager;

    /**
     * @var Installer
     */
    private \ByDN\Framework\Setup\Installer $installer;

    /**
     * Application configuration
     *
     * @var Config
     */
    private $config;

    /**
     * @param \Psr\Container\ContainerInterface $objectManager
     * @param Installer $installer
     * @param Config $config
     */
    public function __construct(
        \Psr\Container\ContainerInterface $objectManager,
        \ByDN\Framework\Setup\Installer $installer,
        \ByDN\Framework\App\Config $config
    ) {
        $this->objectManager = $objectManager;
        $this->installer = $installer;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function run() {

        // Check framework is installed
        if (!$this->isInstalled()) {
            $this->_manageInstallRequired();
            return;
        }

        return $this->_run();
    }

    /**
     * Check framework is installed
     *
     * @return false
     */
    private function isInstalled() {
        return false;
    }

    /**
     * Runs the application. Must be implemented in child class.
     *
     * @return mixed
     */
    abstract public function _run();

    /**
     * Manages application not installed error. Must be implemented in child class.
     *
     * @return mixed
     */
    abstract public function _manageInstallRequired();
}
