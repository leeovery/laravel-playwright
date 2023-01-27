<?php

namespace Leeovery\LaravelPlaywright\Commands\Database\Connectors;

class DryRunConnector implements Connector
{
    public function __construct(private $output)
    {
    }

    public function exec(string $sql)
    {
        return $this->output->writeln(sprintf(
            '<info>[DRY RUN] %s</info>',
            $sql
        ));
    }
}
