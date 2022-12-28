<?php

namespace Leeovery\LaravelPlaywright\Actions;

class EnvTeardownAction extends BaseEnvAction
{
    public function __invoke()
    {
        if (file_exists(base_path($this->backupEnvName))) {
            $this->restoreEnvironment();
            $this->refreshEnvironment();
        }
    }
}
