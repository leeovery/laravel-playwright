<?php

declare(strict_types=1);

beforeEach(function () {
    config()->set('playwright.environments', 'testing');
});

it('rejects factory request without model parameter', function () {
    $this->postJson('/__playwright__/factory', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['model']);
});

it('rejects factory request with invalid count', function () {
    $this->postJson('/__playwright__/factory', [
        'model' => 'User',
        'count' => 'invalid',
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['count']);
});

it('rejects factory request with negative count', function () {
    $this->postJson('/__playwright__/factory', [
        'model' => 'User',
        'count' => -1,
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['count']);
});
