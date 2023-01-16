<?php

namespace Leeovery\LaravelPlaywright;

use Leeovery\LaravelPlaywright\Commands\Database\CreateDatabaseCommand;
use Leeovery\LaravelPlaywright\Commands\Database\DropDatabaseCommand;
use Leeovery\LaravelPlaywright\Commands\Database\ReCreateDatabaseCommand;
use Leeovery\LaravelPlaywright\Commands\LaravelPlaywrightEnvSetup;
use Leeovery\LaravelPlaywright\Commands\LaravelPlaywrightEnvTeardown;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider as ServiceProvider;

class LaravelPlaywrightServiceProvider extends ServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-playwright')
            ->hasConfigFile('laravel-playwright')
            ->hasRoute('playwright')
            ->hasCommands(
                LaravelPlaywrightEnvSetup::class,
                LaravelPlaywrightEnvTeardown::class,
                CreateDatabaseCommand::class,
                DropDatabaseCommand::class,
                ReCreateDatabaseCommand::class,
            );
    }
}
