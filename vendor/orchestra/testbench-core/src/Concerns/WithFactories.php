<?php

namespace Orchestra\Testbench\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Factory as ModelFactory;
use Orchestra\Testbench\Exceptions\ApplicationNotAvailableException;

trait WithFactories
{
    /**
     * Load model factories from path.
     *
     * @param  string  $path
     * @return $this
     *
     * @throws \Exception
     */
    protected function withFactories(string $path)
    {
        if (\is_null($this->app)) {
            throw ApplicationNotAvailableException::make(__METHOD__);
        }

        return $this->loadFactoriesUsing($this->app, $path);
    }

    /**
     * Load model factories from path using Application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  string  $path
     * @return $this
     *
     * @throws \Exception
     */
    protected function loadFactoriesUsing($app, string $path)
    {
        if (! class_exists(ModelFactory::class)) {
            throw new Exception(<<<'requirement'
Missing `laravel/legacy-factories` in composer.json. Please refer to <https://github.com/orchestral/testbench/blob/6.x/README.md#using-legacy-factories>
requirement);
        }

        $app->make(ModelFactory::class)->load($path);

        return $this;
    }
}
