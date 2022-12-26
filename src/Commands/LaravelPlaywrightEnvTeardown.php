<?php

namespace Leeovery\LaravelPlaywright\Commands;

class LaravelPlaywrightEnvTeardown extends LaravelPlaywrightEnv
{
    protected $signature = 'playwright:env-teardown';

    protected $description = 'Teardown temp env for frontend Playwright test suite.';

    public function handle()
    {
        if (file_exists(base_path('.env.backup'))) {
            $this->restoreEnvironment();
            $this->refreshEnvironment();
        }
    }
}
