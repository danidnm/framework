<?php

return [
    'cron.schedule' => [
        'class' => \ByDN\Cron\Console\Schedule::class,
    ],
    'cron.run' => [
        'class' => \ByDN\Cron\Console\Cron::class,
    ],
];
