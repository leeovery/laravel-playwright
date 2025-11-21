<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Create test env files
    File::put(base_path('.env'), 'APP_ENV=testing');
    File::put(base_path('.playwright.env'), 'APP_ENV=playwright');
});

afterEach(function () {
    // Clean up test files
    File::delete(base_path('.env.backup'));
});

it('env setup command swaps environment files', function () {
    config()->set('playwright.env.name', '.playwright.env');

    $this->artisan('playwright:env-setup')->assertSuccessful();

    expect(file_get_contents(base_path('.env')))
        ->toContain('playwright');
});

it('env teardown restores original environment', function () {
    // First setup
    config()->set('playwright.env.name', '.playwright.env');
    $this->artisan('playwright:env-setup')->assertSuccessful();

    // Then teardown
    $this->artisan('playwright:env-teardown')->assertSuccessful();

    expect(file_get_contents(base_path('.env')))
        ->toContain('testing');
});

it('env setup handles files comparison', function () {
    File::put(base_path('.env'), 'SAME=true');
    File::put(base_path('.playwright.env'), 'SAME=true');

    config()->set('playwright.env.name', '.playwright.env');

    $this->artisan('playwright:env-setup')->assertSuccessful();

    // Command ran successfully
    expect(true)->toBeTrue();
});

it('env teardown does nothing without backup', function () {
    File::delete(base_path('.env.backup'));

    $this->artisan('playwright:env-teardown')->assertSuccessful();

    expect(file_get_contents(base_path('.env')))
        ->toContain('testing');
});
