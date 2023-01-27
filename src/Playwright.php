<?php

namespace Leeovery\LaravelPlaywright;

use Closure;

class Playwright
{
    public static array $aliasCallbacks = [];

    public static function alias(string $alias, Closure $callable): static
    {
        static::$aliasCallbacks[$alias] = $callable;

        return new static;
    }

    public static function getAlias(string $alias): callable|null
    {
        return data_get(static::$aliasCallbacks, $alias);
    }
}
