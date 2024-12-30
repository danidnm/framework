<?php

return [
    'cron.schedule' => [
        'class' => \ByDN\Framework\Console\Schedule::class,
    ],
    'cron.run' => [
        'class' => \ByDN\Framework\Console\Cron::class,
    ],
    'module.install' => [
        'class' => \ByDN\Framework\Console\Install::class,
    ],
    'module.uninstall' => [
        'class' => \ByDN\Framework\Console\Uninstall::class,
    ],
];
