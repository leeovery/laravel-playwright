<?php

namespace Leeovery\LaravelPlaywright\Commands\Database;

class CreateDatabaseCommand extends AbstractDatabaseCommand
{
    protected $signature = 'db:create
                                {--database= : The database name to create}
                                {--connection= : The database connection to use}
                                {--pretend}';

    protected $description = 'Creates a database.';

    public function handle(): int
    {
        $this->showIfPretendMode();

        if ($this->createBuilder()->createDatabase($this->getConfig())) {
            $this->info(sprintf('Database "%s" created successfully.', $this->getConfig('database')));

            return 0;
        }

        $this->error('Could not create the database.');

        return 1;
    }
}
