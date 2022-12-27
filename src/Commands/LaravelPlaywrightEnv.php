<?php

namespace Leeovery\LaravelPlaywright\Commands;

use Dotenv\Dotenv;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class LaravelPlaywrightEnv extends Command
{
    abstract public function handle();

    protected function restoreEnvironment(): void
    {
        copy(base_path('.env.backup'), base_path('.env'));
        unlink(base_path('.env.backup'));
    }

    protected function backupEnvironment(): void
    {
        copy(base_path('.env'), base_path('.env.backup'));
        copy(base_path($this->appTestEnvFile()), base_path('.env'));
    }

    protected function appTestEnvFile(): string
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
        Artisan::call('config:clear');
        Dotenv::createMutable(base_path())->load();
        DB::reconnect();
        Artisan::call('config:cache');
    }
}
