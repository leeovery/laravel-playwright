<?php

declare(strict_types=1);

use Leeovery\LaravelPlaywright\Playwright;

it('can register param aliases', function () {
    Playwright::alias('test', fn ($value) => "test_{$value}");

    expect(Playwright::getAlias('test'))
        ->toBeCallable();
});

it('returns null for non-existent alias', function () {
    expect(Playwright::getAlias('non_existent'))
        ->toBeNull();
});

it('can execute registered alias', function () {
    Playwright::alias('double', fn ($value) => $value * 2);

    $callable = Playwright::getAlias('double');

    expect($callable(5))->toBe(10);
});
