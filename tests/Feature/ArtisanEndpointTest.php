<?php

declare(strict_types=1);

beforeEach(function () {
    config()->set('playwright.environments', 'testing');
});

it('executes artisan command via endpoint', function () {
    $this->postJson('/__playwright__/artisan', [
        'command' => 'about',
        'parameters' => [],
    ])->assertStatus(202);
});

it('executes artisan command with parameters', function () {
    $this->postJson('/__playwright__/artisan', [
        'command' => 'list',
        'parameters' => ['--format' => 'json'],
    ])->assertStatus(202);
});

it('rejects artisan request without command', function () {
    $this->postJson('/__playwright__/artisan', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['command']);
});

it('logs out user via endpoint', function () {
    $this->postJson('/__playwright__/logout')
        ->assertOk();
});
