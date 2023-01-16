<?php

namespace Leeovery\LaravelPlaywright\Commands\Database\Schema;

use Leeovery\LaravelPlaywright\Commands\Database\Connectors\Connector;

readonly class Builder
{
    public function __construct(private Connector $connector, private GrammarFactory $grammars)
    {
    }

    public function dropDatabase(array $options)
    {
        $grammar = $this->grammars->make($options['driver']);

        return $this->connector->exec(
            $grammar->compileDropDatabase($options['database'])
        );
    }

    public function createDatabase(array $options)
    {
        $grammar = $this->grammars->make($options['driver']);

        return $this->connector->exec(
            $grammar->compileCreateDatabase($options)
        );
    }
}
