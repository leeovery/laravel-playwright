<?php

namespace Leeovery\LaravelPlaywright\Http\Middleware;

use Closure;

class PreventInNonPermittedEnvironment
{
    public function handle($request, Closure $next)
    {
        $permitted = str(config('laravel-playwright.environments'))
            ->explode(',')
            ->collect()
            ->filter(fn(string $environment) => app()->environment($environment));

        abort_if($permitted->isEmpty(), '404', 'Environment not supported');

        return $next($request);
    }
}
