<?php

declare(strict_types=1);

beforeEach(function () {
    config()->set('playwright.environments', 'testing');
});

it('runs migrations via endpoint', function () {
    $this->postJson('/__playwright__/migrate', [
        'seed' => false,
        'fresh' => false,
    ])->assertStatus(202);
});

it('runs fresh migrations', function () {
    $this->postJson('/__playwright__/migrate', [
        'fresh' => true,
    ])->assertStatus(202);
});

it('accepts seeding parameter', function () {
    // Seeding requires DatabaseSeeder to exist, which we don't have in package tests
    // Just verify the parameter is accepted
    $response = $this->postJson('/__playwright__/migrate', [
        'seed' => false,
    ]);

    expect($response->status())->toBe(202);
});

it('validates boolean parameters for migrations', function () {
    $this->postJson('/__playwright__/migrate', [
        'seed' => 'not-a-bool',
    ])->assertStatus(422);
});
