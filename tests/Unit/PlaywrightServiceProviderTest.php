<?php

declare(strict_types=1);

use Leeovery\LaravelPlaywright\PlaywrightServiceProvider;

it('registers the service provider', function () {
    expect(app()->getProvider(PlaywrightServiceProvider::class))
        ->toBeInstanceOf(PlaywrightServiceProvider::class);
});

it('loads package configuration', function () {
    expect(config('playwright'))
        ->toBeArray()
        ->toHaveKeys(['environments', 'route', 'env', 'factory']);
});

it('has default environment configuration', function () {
    expect(config('playwright.environments'))
        ->toContain('local')
        ->toContain('testing');
});

it('has route configuration', function () {
    expect(config('playwright.route.prefix'))
        ->toBe('__playwright__')
        ->and(config('playwright.route.middleware'))
        ->toBe('web');
});
