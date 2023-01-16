<?php

/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Leeovery\LaravelPlaywright\Http\Controllers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class LaravelPlaywrightController
{
    public function migrate(Request $request)
    {
        ray(config('database.connections.mysql.database'));
        Artisan::call('migrate:fresh --schema-path=false'.($request->boolean('seed') ? ' --seed' : ''));

        return response()->json(Artisan::output(), 202);
    }

    public function createDatabase(Request $request)
    {
        Artisan::call('db:create', $this->handleDatabaseRequest($request));

        return response()->json(Artisan::output(), 202);
    }

    private function handleDatabaseRequest(Request $request): array
    {
        $request->validate([
            'database' => [
                'required',
                'string',
                'max:255',
            ],
            'connection' => [
                'nullable',
                'string',
                'max:255',
            ],
            'pretend' => [
                'nullable',
                'bool',
            ],
        ]);

        $options = [];
        if ($request->has('database')) {
            $options['--database'] = $request->input('database');
        }
        if ($request->has('connection')) {
            $options['--connection'] = $request->input('connection');
        }
        if ($request->has('pretend')) {
            $options['--pretend'] = true;
        }

        return $options;
    }

    public function dropDatabase(Request $request)
    {
        Artisan::call('db:drop', $this->handleDatabaseRequest($request));

        return response()->json(Artisan::output(), 202);
    }

    public function truncate(Request $request)
    {
        $request->validate([
            'tables' => [
                'required',
                'string',
            ],
        ]);

        str($request->input('tables'))->explode(',')->each(function (string $table) {
            DB::table($table)->truncate();
        });

        return response(status: 202);
    }

    public function setupEnv()
    {
        Artisan::call('playwright:env-setup');

        return response()->json(Artisan::output(), 202);
    }

    public function tearDownEnv()
    {
        Artisan::call('playwright:env-teardown');

        return response()->json(Artisan::output(), 202);
    }

    public function routes()
    {
        return collect(Route::getRoutes()->getRoutes())
            ->reject(fn (RoutingRoute $route) => Str::of($route->getName())
                ->contains(config('laravel-playwright.route.ignore_names'))
            )
            ->reject(fn (RoutingRoute $route) => is_null($route->getName()))
            ->mapWithKeys(fn (RoutingRoute $route) => [
                $route->getName() => [
                    'name' => $route->getName(),
                    'uri' => $route->uri(),
                    'method' => $route->methods(),
                    'domain' => $route->getDomain(),
                ],
            ]);
    }

    public function user()
    {
        return response()->json(auth()->user()?->setHidden([])->setVisible([]));
    }

    public function login(Request $request)
    {
        $attributes = $request->input('attributes', []);
        $user = null;

        if (filled($attributes)) {
            $user = resolve($this->userClassName($request))
                ->newQuery()
                ->where($attributes)
                ->first();
        }

        if (! $user) {
            $user = $this
                ->factoryBuilder($this->userClassName($request), $request->input('state', []))
                ->create();
        }

        $user->load($request->input('load', []));

        return tap($user, function ($user) {
            auth()->login($user);

            $user->setHidden([])->setVisible([]);
        });
    }

    protected function userClassName(Request $request)
    {
        if ($request->has('userModel')) {
            return $this->resolveModelAlias($request->input('userModel'));
        }

        return config('laravel-playwright.factory.user');
    }

    protected function resolveModelAlias(string $alias)
    {
        return data_get(config('laravel-playwright.factory.models'), $alias, $alias);
    }

    protected function factoryBuilder($model, $states = []): Factory
    {
        $factory = $this->resolveModelAlias($model)::factory();

        $stateSeparator = config('laravel-playwright.factory.state_separator');
        $modelSeparator = config('laravel-playwright.factory.model_separator');

        foreach (Arr::wrap($states) as $state) {
            $attributes = [];
            if (is_array($state)) {
                $attributes = collect(...array_values($state))->map(function ($attribute) use (
                    $modelSeparator,
                    $stateSeparator
                ) {
                    if (! is_string($attribute) || ! str_contains($attribute, $stateSeparator)) {
                        return $attribute;
                    }

                    [$model, $id] = explode($stateSeparator, $attribute);
                    [$id, $column] = array_pad(explode($modelSeparator, $id), 2, null);
                    $column ??= 'id';

                    return $this->resolveModelAlias($model)::where($column, $id)->first();
                })->filter()->all();

                $state = array_key_first($state);
            }

            $factory = $factory->{$state}(...Arr::wrap($attributes));
        }

        return $factory;
    }

    public function factory(Request $request)
    {
        $request->validate([
            'model' => [
                'required',
                'string',
            ],
            'count' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'state' => [
                'nullable',
                'array',
            ],
            'attributes' => [
                'nullable',
                'array',
            ],
            'load' => [
                'nullable',
                'array',
            ],
            'load.*' => [
                'string',
            ],
        ]);

        return $this
            ->factoryBuilder(
                model: $request->input('model'),
                states: $request->input('state') ?? []
            )
            ->count($request->integer('count', 1))
            ->create($request->input('attributes'))
            ->each(fn ($model) => $model->setHidden([])->setVisible([]))
            ->load($request->input('load') ?? [])
            ->pipe(fn ($collection) => $collection->count() > 1
                ? $collection
                : $collection->first());
    }

    public function logout()
    {
        auth()->logout();
    }

    public function artisan(Request $request)
    {
        $request->validate([
            'command' => [
                'required',
                'string',
            ],
            'parameters' => [
                'nullable',
                'array',
            ],
        ]);

        Artisan::call(
            command: $request->input('command'),
            parameters: $request->input('parameters', [])
        );

        return response()->json(Artisan::output(), 202);
    }

    public function csrf()
    {
        return response()->json(csrf_token());
    }
}
