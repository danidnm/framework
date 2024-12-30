<?php

return [

    \Cron\CronExpression::class => DI\factory(
        function (\Psr\Container\ContainerInterface $c) {
            return  new \Cron\CronExpression('* * * * *');
        }
    ),

];
