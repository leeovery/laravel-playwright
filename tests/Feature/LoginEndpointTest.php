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

it('logs in existing user by attributes', function () {
    $user = User::create([
        'name' => 'Existing User',
        'email' => 'existing@example.com',
        'password' => 'password',
    ]);

    $response = $this->postJson('/__playwright__/login', [
        'attributes' => ['email' => 'existing@example.com'],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect(auth()->id())->toBe($user->id);
});

it('creates and logs in user when attributes do not match existing user', function () {
    $response = $this->postJson('/__playwright__/login', [
        'attributes' => ['email' => 'new@example.com'],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect(auth()->check())->toBeTrue();
});

it('creates user with state and logs them in', function () {
    $response = $this->postJson('/__playwright__/login', [
        'state' => ['admin'],
        'attributes' => ['email' => 'admin@example.com'],
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect(auth()->user()->is_admin)->toBeTrue();
});

it('creates user without any parameters', function () {
    $response = $this->postJson('/__playwright__/login');

    expect($response->status())->toBeIn([200, 201]);
    expect(auth()->check())->toBeTrue();
});

it('logs out user', function () {
    User::create([
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $this->postJson('/__playwright__/login', [
        'attributes' => ['email' => 'test@example.com'],
    ]);

    expect(auth()->check())->toBeTrue();

    $this->postJson('/__playwright__/logout')->assertOk();

    expect(auth()->check())->toBeFalse();
});
