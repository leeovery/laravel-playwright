<?php

namespace Leeovery\LaravelPlaywright\Commands\Database\Connectors;

interface Connector
{
    public function exec(string $sql);
}
