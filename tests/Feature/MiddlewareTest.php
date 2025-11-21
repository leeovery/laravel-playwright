<?php

declare(strict_types=1);

it('blocks requests in non-permitted environments', function () {
    config()->set('playwright.environments', 'production,staging');

    $this->getJson('/__playwright__/csrf')
        ->assertStatus(404);
});

it('allows requests in permitted environments', function () {
    config()->set('playwright.environments', 'testing,local');

    $this->getJson('/__playwright__/csrf')
        ->assertOk();
});

it('handles multiple permitted environments', function () {
    config()->set('playwright.environments', 'local,testing,playwright');

    $this->getJson('/__playwright__/csrf')
        ->assertOk();
});
