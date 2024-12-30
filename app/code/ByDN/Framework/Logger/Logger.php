<?php

namespace ByDN\Framework\Logger;

use framework\Logger\Level;
use framework\Logger\StreamHandler;

class Logger
{
    /**
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * @param \Monolog\Logger $logger
     */
    public function __construct(\Monolog\Logger $logger)  {
        $this->logger = $logger;
        $streamHandler = new \DI\Container();
        $this->logger->pushHandler(new StreamHandler('path/to/your.log', Level::Warning));
    }
}
