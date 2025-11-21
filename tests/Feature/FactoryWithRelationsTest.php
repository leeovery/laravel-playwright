<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Leeovery\LaravelPlaywright\Tests\Fixtures\User;

beforeEach(function () {
    config()->set('playwright.environments', 'testing');
    config()->set('playwright.factory.user', User::class);

    Schema::create('users', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->boolean('is_admin')->default(false);
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::dropIfExists('users');
});

it('creates factory with empty load array', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'load' => [],
    ]);

    expect($response->status())->toBeIn([200, 201]);
});

it('validates state must be array', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'state' => 'admin',
    ]);

    expect($response->status())->toBe(422);
});

it('creates factory with array state', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'state' => ['admin'],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect($response->json('is_admin'))->toBeTrue();
});

it('creates factory with complex state configuration', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'state' => [
            ['admin' => []],
        ],
    ]);

    expect($response->status())->toBeIn([200, 201]);
});

it('creates factory with count of 1 returns object not array', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'count' => 1,
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect($response->json())->toHaveKey('id');
    expect($response->json())->toHaveKey('email');
});

it('creates factory with count greater than 1 returns array', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'count' => 2,
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect($response->json())->toBeArray();
    expect($response->json())->toHaveCount(2);
});
