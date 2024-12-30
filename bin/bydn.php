<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var \ByDN\Framework\App\Bootstrap $bootstrap */
$bootstrap = \ByDN\Framework\App\Bootstrap::create();

/** @var \ByDN\Framework\App\Console $app */
$app = $bootstrap->createApplication(\ByDN\Framework\App\Console::class, $argv);

$bootstrap->run($app);

