<?php

return [

    "mysql.host" => "localhost",
    "mysql.database" => "trading",
    "mysql.username" => "trading",
    "mysql.password" => "trading",
    "mysql.post" => "3306",

    \ByDN\Framework\Model\DbConnector::class => DI\factory(
        function (\Psr\Container\ContainerInterface $c) {
            $dbConnector = new \ByDN\Framework\Model\DbConnector(
                $c->get('mysql.host'),
                $c->get('mysql.username'),
                $c->get('mysql.password'),
                $c->get('mysql.database'),
                $c->get('mysql.post')
            );
            // TODO: Load timezone from config
            $dbConnector->query("SET time_zone = 'Europe/Madrid';");
            return $dbConnector;
        }
    ),


    \Cron\CronExpression::class => DI\factory(
        function (\Psr\Container\ContainerInterface $c) {
            return  new \Cron\CronExpression('* * * * *');
        }
    ),

//    \Psr\Log\LoggerInterface::class => DI\factory(
//        function (\Psr\Container\ContainerInterface $c) {
//            $logger = new \Monolog\Logger($c->get('logger.name'));
//            $fileHandler = new \Monolog\Handler\StreamHandler($c->get('logger.path'),  \Monolog\Level::Warning);
//            $fileHandler->setFormatter(new \Monolog\Formatter\LineFormatter());
//            $logger->pushHandler($fileHandler);
//            return $logger;
//        }
//    ),
];
