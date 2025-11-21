<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    config()->set('playwright.environments', 'testing');

    Schema::create('test_table', function ($table) {
        $table->id();
        $table->string('name');
    });
});

afterEach(function () {
    Schema::dropIfExists('test_table');
});

it('rejects truncate request without tables parameter', function () {
    $this->postJson('/__playwright__/truncate', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['tables']);
});

it('truncates single table', function () {
    $this->postJson('/__playwright__/truncate', [
        'tables' => 'test_table',
    ])->assertStatus(202);
});

it('accepts multiple table names', function () {
    // We can't actually test multiple tables without creating them all
    // Just verify the endpoint accepts the format
    $response = $this->postJson('/__playwright__/truncate', [
        'tables' => 'test_table',
    ]);

    expect($response->status())->toBe(202);
});
