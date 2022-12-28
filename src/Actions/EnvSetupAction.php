<?php

namespace Leeovery\LaravelPlaywright\Actions;

class EnvSetupAction extends BaseEnvAction
{
    public function __invoke()
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
