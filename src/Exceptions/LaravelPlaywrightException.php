<?php

declare(strict_types=1);

namespace Leeovery\LaravelPlaywright\Exceptions;

use Exception;

class LaravelPlaywrightException extends Exception
{
    public static function resolvedModelDoesNotExist($model): self
    {
        return new self("Passed model does not exist ({$model}).", 404);
    }

    public static function resolvedParamAliasDoesNotExist($alias): self
    {
        return new self("Passed param alias does not exist ({$alias}).", 404);
    }
}
