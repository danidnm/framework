<?php

namespace ByDN\Framework\Console;

class Uninstall implements \ByDN\Framework\App\CommandInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $objectManager;

    /**
     * @var \ByDN\Framework\App\Config
     */
    private $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Command arguments
     *
     * @var array
     */
    private $arguments = [];

    /**
     * @param \Psr\Container\ContainerInterface $objectManager
     * @param \ByDN\Framework\App\Config $config
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Container\ContainerInterface $objectManager,
        \ByDN\Framework\App\Config $config,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->objectManager = $objectManager;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param $arguments
     * @return void
     */
    public function run($arguments)
    {
        // Store arguments locally
        $this->arguments = $arguments;

        // Module
        $module = $this->arguments['module'];

        // Installer class
        $uninstallerClass = $this->config->getModuleNamespace($module) . '\Setup\Uninstaller';

        /**
         * @var \ByDN\Framework\Setup\UninstallerInterface $uninstaller
         */
        $uninstaller = $this->objectManager->get($uninstallerClass);
        $uninstaller->uninstall();
    }}