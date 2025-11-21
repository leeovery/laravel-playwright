<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

beforeEach(function () {
    File::put(base_path('.env'), 'APP_ENV=testing');
});

afterEach(function () {
    if (File::exists(base_path('.env.backup'))) {
        File::delete(base_path('.env.backup'));
    }
    if (File::exists(base_path('.playwright.env'))) {
        File::delete(base_path('.playwright.env'));
    }
});

it('env setup handles missing playwright env file', function () {
    config()->set('playwright.env.name', '.nonexistent.env');

    $this->artisan('playwright:env-setup');

    // No error should occur
    expect(true)->toBeTrue();
})->throws(Exception::class);

it('env setup finds environment-specific file', function () {
    File::put(base_path('.playwright.env.testing'), 'APP_ENV=playwright_testing');
    config()->set('playwright.env.name', '.playwright.env');

    $this->artisan('playwright:env-setup')->assertSuccessful();

    expect(file_get_contents(base_path('.env')))
        ->toContain('playwright_testing');
});
