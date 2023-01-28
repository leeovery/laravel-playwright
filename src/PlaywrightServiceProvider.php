<?php

namespace Leeovery\LaravelPlaywright;

use Leeovery\LaravelPlaywright\Commands\Database\CreateDatabaseCommand;
use Leeovery\LaravelPlaywright\Commands\Database\DropDatabaseCommand;
use Leeovery\LaravelPlaywright\Commands\PlaywrightEnvSetup;
use Leeovery\LaravelPlaywright\Commands\PlaywrightEnvTeardown;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider as ServiceProvider;

class PlaywrightServiceProvider extends ServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-playwright')
            ->hasConfigFile()
            ->hasRoute('playwright')
            ->publishesServiceProvider('PlaywrightServiceProvider')
            ->hasCommands(
                PlaywrightEnvSetup::class,
                PlaywrightEnvTeardown::class,
                CreateDatabaseCommand::class,
                DropDatabaseCommand::class,
            )
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('leeovery/laravel-playwright');
            });
    }
}
