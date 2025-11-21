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

it('creates single model via factory endpoint', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'count' => 1,
        'attributes' => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    $data = $response->json();
    expect($data['name'])->toBe('John Doe');
    expect($data['email'])->toBe('john@example.com');
});

it('creates multiple models via factory endpoint', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'count' => 3,
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect($response->json())->toHaveCount(3);
});

it('creates model with state', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'state' => ['admin'],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect($response->json('is_admin'))->toBeTrue();
});

it('creates model with load relationship', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => User::class,
        'load' => [],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect($response->json())->toHaveKey('name');
});

it('logs in user with attributes', function () {
    User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response = $this->postJson('/__playwright__/login', [
        'attributes' => ['email' => 'test@example.com'],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect($response->json('email'))->toBe('test@example.com');
    expect(auth()->check())->toBeTrue();
    expect(auth()->user()->email)->toBe('test@example.com');
});

it('creates new user when login attributes not found', function () {
    $response = $this->postJson('/__playwright__/login', [
        'attributes' => ['email' => 'notfound@example.com'],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    // Factory creates user with random email since attributes not found
    expect(auth()->check())->toBeTrue();
    expect(auth()->user())->toBeInstanceOf(User::class);
});

it('creates user without attributes via login', function () {
    $response = $this->postJson('/__playwright__/login');

    expect($response->status())->toBeIn([200, 201]);
    expect(auth()->check())->toBeTrue();
    expect(auth()->user())->toBeInstanceOf(User::class);
});

it('logs in user with state', function () {
    $response = $this->postJson('/__playwright__/login', [
        'state' => ['admin'],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect($response->json('is_admin'))->toBeTrue();
    expect(auth()->check())->toBeTrue();
});

it('logs in user with load parameter', function () {
    $response = $this->postJson('/__playwright__/login', [
        'load' => [],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect(auth()->check())->toBeTrue();
});
