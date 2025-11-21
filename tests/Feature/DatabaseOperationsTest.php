<?php

declare(strict_types=1);

use Leeovery\LaravelPlaywright\Exceptions\LaravelPlaywrightException;

beforeEach(function () {
    config()->set('playwright.environments', 'testing');
});

it('rejects create database request without database parameter', function () {
    $this->postJson('/__playwright__/create-database', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['database']);
});

it('rejects drop database request without database parameter', function () {
    $this->postJson('/__playwright__/drop-database', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['database']);
});

it('throws exception when model does not exist', function () {
    config()->set('playwright.factory.model_aliases', [
        'TestModel' => 'App\\Models\\NonExistent',
    ]);

    $this->postJson('/__playwright__/factory', [
        'model' => 'TestModel',
    ])->assertStatus(500);
});

it('creates exception with model name in message', function () {
    $exception = LaravelPlaywrightException::resolvedModelDoesNotExist('App\\Models\\Test');

    expect($exception)
        ->toBeInstanceOf(LaravelPlaywrightException::class)
        ->getMessage()->toContain('App\\Models\\Test')
        ->and($exception->getCode())->toBe(404);
});

it('creates exception with param alias name in message', function () {
    $exception = LaravelPlaywrightException::resolvedParamAliasDoesNotExist('InvalidAlias');

    expect($exception)
        ->toBeInstanceOf(LaravelPlaywrightException::class)
        ->getMessage()->toContain('InvalidAlias')
        ->and($exception->getCode())->toBe(404);
});
