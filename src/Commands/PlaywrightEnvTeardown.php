<?php

declare(strict_types=1);

namespace Leeovery\LaravelPlaywright\Commands;

class PlaywrightEnvTeardown extends PlaywrightEnv
{
    protected $signature = 'playwright:env-teardown';

    protected $description = 'Teardown temp env for frontend Playwright test suite.';

    public function handle(): void
    {
        if (file_exists(base_path($this->backupEnvName))) {
            $this->restoreEnvironment();
            $this->refreshEnvironment();
        }
    }
}
