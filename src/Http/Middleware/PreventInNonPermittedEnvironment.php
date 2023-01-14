<?php

namespace Leeovery\LaravelPlaywright\Http\Middleware;

use Closure;

class PreventInNonPermittedEnvironment
{
    public function handle($request, Closure $next)
    {
        str(config('laravel-playwright.environments'))
            ->explode(',')
            ->collect()
            ->filter(fn(string $environment) => app()->environment($environment))
            ->whenEmpty(function () {
                abort('404');
            });

        return $next($request);
    }
}
