<?php

namespace Leeovery\LaravelPlaywright\Commands\Database\Schema;

use Leeovery\LaravelPlaywright\Commands\Database\Schema\Grammars\MySQL;
use Leeovery\LaravelPlaywright\Commands\Database\Schema\Grammars\PgSQL;
use Leeovery\LaravelPlaywright\Commands\Database\Schema\Grammars\SQL;
use RuntimeException;

class GrammarFactory
{
    private static array $availableOptions = [
        'mysql' => MySQL::class,
        'pgsql' => PgSQL::class,
    ];

    public function make(string $driver): SQL
    {
        if (! array_key_exists($driver, static::$availableOptions)) {
            throw new RuntimeException(sprintf('Unknown driver "%s".', $driver));
        }

        $grammar = static::$availableOptions[$driver];

        return new $grammar;
    }
}
