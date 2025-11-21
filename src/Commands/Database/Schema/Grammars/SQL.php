<?php

declare(strict_types=1);

namespace Leeovery\LaravelPlaywright\Commands\Database\Schema\Grammars;

interface SQL
{
    public function compileCreateDatabase(array $options): string;

    public function compileDropDatabase(string $database): string;
}
