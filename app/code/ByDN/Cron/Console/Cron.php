<?php

namespace ByDN\Cron\Console;

class Cron implements \ByDN\Framework\App\CommandInterface
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

    public function run($arguments)
    {
        $this->logger->debug(__METHOD__ . ': ini');



        $this->logger->debug(__METHOD__ . ': end');
    }
}