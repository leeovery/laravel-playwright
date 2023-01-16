<?php

namespace Leeovery\LaravelPlaywright\Commands\Database;

class DropDatabaseCommand extends AbstractDatabaseCommand
{
    protected $signature = 'db:drop 
                                {--database= : The database name to drop} 
                                {--connection= : The database connection to use} 
                                {--pretend}';

    protected $description = 'Drops database.';

    public function handle(): int
    {
        $this->showIfPretendMode();

        if ($this->createBuilder()->dropDatabase($this->getConfig()) === false) {
            $this->error('Could not drop the database.');

            return 1;
        }

        $this->info(sprintf('Database "%s" dropped successfully.', $this->getConfig('database')));

        return 0;
    }
}
