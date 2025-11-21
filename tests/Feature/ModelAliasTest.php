<?php

declare(strict_types=1);

use Leeovery\LaravelPlaywright\Tests\Fixtures\User;

beforeEach(function () {
    config()->set('playwright.environments', 'testing');
    config()->set('playwright.factory.model_aliases', [
        'User' => User::class,
        'TestUser' => User::class,
    ]);

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

it('resolves model alias in factory request', function () {
    $response = $this->postJson('/__playwright__/factory', [
        'model' => 'User',
        'count' => 1,
    ]);

    expect($response->status())->toBeIn([200, 201]);
});

it('resolves model alias in login request', function () {
    $response = $this->postJson('/__playwright__/login', [
        'userModel' => 'TestUser',
    ]);

    expect($response->status())->toBeIn([200, 201]);
    expect(auth()->check())->toBeTrue();
});

it('uses configured default user model when no userModel specified', function () {
    config()->set('playwright.factory.user', User::class);

    $response = $this->postJson('/__playwright__/login');

    expect($response->status())->toBeIn([200, 201]);
    expect(auth()->user())->toBeInstanceOf(User::class);
});
