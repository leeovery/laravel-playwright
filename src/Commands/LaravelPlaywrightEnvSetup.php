<?php

namespace Leeovery\LaravelPlaywright\Commands;

class LaravelPlaywrightEnvSetup extends LaravelPlaywrightEnv
{
    protected $signature = 'playwright:env-setup';

    protected $description = 'Setup temp env for frontend Playwright test suite.';

    public function handle()
    {
        if (! file_exists(base_path($this->appTestEnvFile()))) {
            return;
        }

        if (
            file_get_contents(base_path('.env')) !==
            file_get_contents(base_path($this->appTestEnvFile()))
        ) {
            $this->backupEnvironment();
        }

        $this->refreshEnvironment();
    }
}
