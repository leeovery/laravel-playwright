<?php

use Illuminate\Support\Facades\Route;
use Leeovery\LaravelPlaywright\Http\Controllers\LaravelPlaywrightController;
use Leeovery\LaravelPlaywright\Http\Middleware\PreventInNonPermittedEnvironment;

Route::prefix(config('laravel-playwright.route.prefix'))
    ->middleware([
        PreventInNonPermittedEnvironment::class,
        config('laravel-playwright.route.middleware'),
    ])
    ->group(function () {
        Route::post('/env-setup', [LaravelPlaywrightController::class, 'setupEnv'])
            ->name('playwright.setup-env');
        Route::post('/env-teardown', [LaravelPlaywrightController::class, 'tearDownEnv'])
            ->name('playwright.tear-down-env');
        Route::post('/migrate', [LaravelPlaywrightController::class, 'migrate'])
            ->name('playwright.migrate');
        Route::post('/create-database', [LaravelPlaywrightController::class, 'createDatabase'])
            ->name('playwright.create-database');
        Route::post('/drop-database', [LaravelPlaywrightController::class, 'dropDatabase'])
            ->name('playwright.drop-database');
        Route::post('/truncate', [LaravelPlaywrightController::class, 'truncate'])
            ->name('playwright.truncate');
        Route::post('/factory', [LaravelPlaywrightController::class, 'factory'])
            ->name('playwright.factory');
        Route::post('/login', [LaravelPlaywrightController::class, 'login'])
            ->name('playwright.login');
        Route::post('/logout', [LaravelPlaywrightController::class, 'logout'])
            ->name('playwright.logout');
        Route::post('/artisan', [LaravelPlaywrightController::class, 'artisan'])
            ->name('playwright.artisan');
        Route::get('/csrf', [LaravelPlaywrightController::class, 'csrf'])
            ->name('playwright.csrf');
        Route::post('/routes', [LaravelPlaywrightController::class, 'routes'])
            ->name('playwright.routes');
        Route::post('/user', [LaravelPlaywrightController::class, 'user'])
            ->name('playwright.user');
    });
