<?php

namespace Leeovery\LaravelPlaywright\Commands;

class PlaywrightEnvSetup extends PlaywrightEnv
{
    protected $signature = 'playwright:env-setup';

    protected $description = 'Setup temp env for frontend Playwright test suite.';

    public function handle()
    {
        if (! file_exists($envFile = base_path($this->getPlaywrightEnvFile()))) {
            return;
        }

        if (file_get_contents(base_path('.env')) !== file_get_contents($envFile)) {
            $this->swapEnvironment();
        }

        $this->refreshEnvironment();
    }
}
