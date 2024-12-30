<?php

return [

    \ByDN\Framework\Model\DbConnector::class => DI\factory(
        function (\ByDN\Framework\App\Config $c) {
            $dbConnector = new \ByDN\Framework\Model\DbConnector(
                $c->getData('config/db.host'),
                $c->getData('config/db.username'),
                $c->getData('config/db.password'),
                $c->getData('config/db.database'),
                $c->getData('config/db.post')
            );
            // TODO: Load timezone from config
            $dbConnector->query("SET time_zone = 'Europe/Madrid';");
            return $dbConnector;
        }
    ),

    \Psr\Log\LoggerInterface::class => DI\factory(
        function (\ByDN\Framework\App\Config $c) {
            $logger = new \Monolog\Logger($c->getData('config/logger.name'));
            $fileHandler = new \Monolog\Handler\StreamHandler($c->getData('config/logger.path'),  \Monolog\Level::Warning);
            $fileHandler->setFormatter(new \Monolog\Formatter\LineFormatter());
            $logger->pushHandler($fileHandler);
            return $logger;
        }
    ),
];
