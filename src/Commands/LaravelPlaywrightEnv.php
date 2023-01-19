<?php

namespace Leeovery\LaravelPlaywright\Commands;

use Dotenv\Dotenv;
use Illuminate\Console\Command;

abstract class LaravelPlaywrightEnv extends Command
{
    protected string $backupEnvName = '.env.backup';

    abstract public function handle();

    protected function restoreEnvironment(): void
    {
        copy(base_path($this->backupEnvName), base_path('.env'));
        unlink(base_path($this->backupEnvName));
    }

    protected function swapEnvironment(): void
    {
        copy(base_path('.env'), base_path($this->backupEnvName));
        copy(base_path($this->getPlaywrightEnvFile()), base_path('.env'));
    }

    protected function getPlaywrightEnvFile(): string
    {
        $envName = config('laravel-playwright.env.name');
        if (file_exists(base_path($file = "{$envName}.{$this->laravel->environment()}"))) {
            return $file;
        }

        if (file_exists(base_path($envName))) {
            return $envName;
        }

        abort(404, 'Playwright env file missing');
    }

    protected function refreshEnvironment(): void
    {
        Dotenv::createMutable(base_path())->load();
    }
}
