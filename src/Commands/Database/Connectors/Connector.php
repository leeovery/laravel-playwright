<?php

declare(strict_types=1);

namespace Leeovery\LaravelPlaywright\Commands\Database\Connectors;

interface Connector
{
    public function exec(string $sql);
}
