<?php

namespace ByDN\Framework\App;

interface CommandInterface
{
    public function run($arguments);
}