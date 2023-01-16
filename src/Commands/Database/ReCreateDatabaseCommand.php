<?php

namespace Leeovery\LaravelPlaywright\Commands\Database;

class ReCreateDatabaseCommand extends AbstractDatabaseCommand
{
    protected $signature = 'db:recreate 
                                {--database= : The database name to create}
                                {--connection= : The database connection to use}
                                {--pretend}';

    protected $description = 'Re-creates the currently configured database.';

    public function handle(): int
    {
        $this->showIfPretendMode();

        if ($this->createBuilder()->recreateDatabase($this->getConfig())) {
            $this->info(sprintf('Database "%s" re-created successfully.', $this->getConfig('database')));

            return 0;
        }

        $this->error('Could not re-created the database.');

        return 1;
    }
}
