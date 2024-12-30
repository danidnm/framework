<?php

namespace ByDN\Framework\App;

interface AppInterface
{
    public function run();
    public function setArguments(array $arguments);
}