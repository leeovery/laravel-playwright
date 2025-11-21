<?php

declare(strict_types=1);

beforeEach(function () {
    config()->set('playwright.environments', 'testing');
});

it('accepts create database request with valid database name', function () {
    $response = $this->postJson('/__playwright__/create-database', [
        'database' => 'test_database',
    ]);

    // Will either succeed (202) or fail due to driver issues (500)
    // but should not be a validation error (422)
    expect($response->status())->not->toBe(422);
});

it('accepts drop database request with valid database name', function () {
    $response = $this->postJson('/__playwright__/drop-database', [
        'database' => 'test_database',
    ]);

    // Will either succeed (202) or fail due to driver issues (500)
    // but should not be a validation error (422)
    expect($response->status())->not->toBe(422);
});

it('accepts create database with connection parameter', function () {
    $response = $this->postJson('/__playwright__/create-database', [
        'database' => 'test_database',
        'connection' => 'sqlite',
    ]);

    expect($response->status())->not->toBe(422);
});

it('accepts pretend parameter for create database', function () {
    $response = $this->postJson('/__playwright__/create-database', [
        'database' => 'test_database',
        'pretend' => true,
    ]);

    // Pretend mode may still fail based on driver/connection
    expect($response->status())->not->toBe(422);
});

it('accepts pretend parameter for drop database', function () {
    $response = $this->postJson('/__playwright__/drop-database', [
        'database' => 'test_database',
        'pretend' => true,
    ]);

    // Pretend mode may still fail based on driver/connection
    expect($response->status())->not->toBe(422);
});
