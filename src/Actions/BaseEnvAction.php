<?php

namespace Leeovery\LaravelPlaywright\Actions;

use Dotenv\Dotenv;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class BaseEnvAction
{
    protected string $backupEnvName = '.env.backup';

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
        $environment = app()->environment();
        if (file_exists(base_path($file = "{$envName}.{$environment}"))) {
            return $file;
        }

        if (file_exists(base_path($envName))) {
            return $envName;
        }

        abort(404, 'Playwright env file missing');
    }

    protected function refreshEnvironment(): void
    {
        Artisan::call('optimize:clear');
        Dotenv::createMutable(base_path())->load();
        Artisan::call('optimize');
        DB::reconnect();
    }
}
