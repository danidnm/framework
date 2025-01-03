<?php

namespace ByDN\Framework\App;

class Console extends AppAbstract
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $objectManager;

    /**
     * Command name to be executed
     * @var string
     */
    protected $commandName = '';

    /**
     * Arguments to pass to the command class
     * @var array
     */
    protected $arguments = [];

    /**
     * Application configuration
     *
     * @var Config
     */
    private $config;

    public function __construct(
        \Psr\Container\ContainerInterface $objectManager,
        \ByDN\Framework\App\Config $config
    ) {
        $this->objectManager = $objectManager;
        $this->config = $config;

        // FIXME: Poner esto en otro sitio
        date_default_timezone_set($this->config->getData('config/timezone'));
    }

    /**
     * @inheritDoc
     */
    public function _run()
    {
        if ($this->commandName) {
            $commandClassName = $this->config->getData('command/' . $this->commandName . '/class');
            $commandClass = $this->objectManager->get($commandClassName);
            $commandClass->run($this->arguments);
        }
        else {
            echo 'Implement list of commands' . PHP_EOL;  // FIXME
            $commands = $this->config->getData('command');
            foreach ($commands as $commandName => $command) {
                echo $commandName . PHP_EOL;  // FIXME
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function _manageInstallRequired()
    {
        // TODO: Implement _manageInstallRequired() method.
    }

    /**
     * @param array $arguments
     * @return void
     */
    public function setArguments(array $arguments)
    {
        // First argument is php file (discard)
        array_shift($arguments);

        // Second argument is command name
        $this->commandName = array_shift($arguments);

        // Rest of the arguments are configuration parameters for the command. Extract as array.
        $this->arguments = [];
        foreach ($arguments as $argument) {
            list($key, $value) = explode('=', substr($argument, 2), 2);
            $this->arguments[$key] = $value;
        }
    }
}
