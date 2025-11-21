<?php

declare(strict_types=1);

beforeEach(function () {
    config()->set('playwright.environments', 'testing');
});

it('returns application routes', function () {
    $response = $this->postJson('/__playwright__/routes');

    $response->assertOk();
    expect($response->json())->toBeArray();
});

it('returns csrf token', function () {
    $response = $this->getJson('/__playwright__/csrf');

    $response->assertOk();
    expect($response->getContent())->toBeString();
});

it('returns current authenticated user', function () {
    $response = $this->postJson('/__playwright__/user');

    $response->assertOk();
});
