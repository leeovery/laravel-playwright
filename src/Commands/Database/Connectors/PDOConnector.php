<?php

namespace Leeovery\LaravelPlaywright\Commands\Database\Connectors;

use PDO;

readonly class PDOConnector implements Connector
{
    public function __construct(private PDO $pdo)
    {
    }

    public static function make(array $config): PDOConnector
    {
        $host = sprintf(
            '%s:host=%s',
            $config['driver'],
            $config['host'] ?? '127.0.0.1'
        );

        return new static(new PDO($host, $config['username'], $config['password']));
    }

    public function exec(string $sql): false|int
    {
        return $this->pdo->exec($sql);
    }
}
