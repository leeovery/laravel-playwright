<?php

namespace Leeovery\LaravelPlaywright\Commands\Database;

use Illuminate\Console\Command;
use Leeovery\LaravelPlaywright\Commands\Database\Connectors\Connector;
use Leeovery\LaravelPlaywright\Commands\Database\Connectors\DryRunConnector;
use Leeovery\LaravelPlaywright\Commands\Database\Connectors\PDOConnector;
use Leeovery\LaravelPlaywright\Commands\Database\Schema\Builder;
use Leeovery\LaravelPlaywright\Commands\Database\Schema\GrammarFactory;

abstract class AbstractDatabaseCommand extends Command
{
    protected array $config;

    protected function showIfPretendMode()
    {
        if ($this->isPretendRunMode()) {
            $this->info('[PRETENDING] Running in pretend mode.');
        }
    }

    protected function isPretendRunMode(): bool
    {
        return (bool) $this->option('pretend');
    }

    protected function createBuilder(): Builder
    {
        return new Builder(
            $this->makeConnector(),
            resolve(GrammarFactory::class)
        );
    }

    protected function makeConnector(): Connector
    {
        if ($this->isPretendRunMode()) {
            return new DryRunConnector($this->output);
        }

        return PDOConnector::make($this->getConfig());
    }

    protected function getConfig(string $key = null): mixed
    {
        if (! $this->config) {
            $connection = $this->option('connection') ?? config('database.default');
            $this->config = config(sprintf('database.connections.%s', $connection));
            $this->config['database'] = $this->option('database') ?? $this->config['database'];
        }

        return $key ? $this->config[$key] : $this->config;
    }
}
