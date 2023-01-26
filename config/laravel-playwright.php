<?php

return [

    /**
     * Comma seperated list of valid environments which you want the Laravel Playwright
     * routes exposed for. Any requests made to the routes in an environment which is
     * not in this list will be rejected.
     */
    'environments' => env('LARAVEL_PLAYWRIGHT_PERMITTED_ENVIRONMENTS', 'local,testing,playwright'),

    'route' => [

        /**
         * Prefix routes with whatever string suits you. This will be added to the
         * beginning of the URLs which are exposed by this package.
         */
        'prefix' => env('LARAVEL_PLAYWRIGHT_ROUTE_PREFIX', '__playwright__'),

        /**
         * All requests to the routes exposed by this package will run through this
         * middleware. Defaults to web.
         */
        'middleware' => env('LARAVEL_PLAYWRIGHT_ROUTE_MIDDLEWARE_NAME', 'web'),

        /**
         * Any routes which contain the following substrings will be rejected from the
         * exposed route list.
         */
        'ignore_names' => [
            'horizon',
            'telescope',
            'playwright',
            'nova',
            'ignition',
        ],

    ],

    'env' => [

        /**
         * When the Playwright tests begin the env file which is present on the backend
         * will be rotated out and replaced with whatever is named here. When the tests
         * end the previous env file will be rotated back into place.
         *
         * If you need environment based env for Playwright you can append the environment
         * name on the end of the env and that will be used if available for the current
         * environment.
         *
         * eg. .playwright.env.staging
         */
        'name' => env('LARAVEL_PLAYWRIGHT_ENV_NAME', '.playwright.env'),

    ],

    'factory' => [

        'user' => 'App\\Models\\User',

        /**
         * When passing state values to the factory, you can include a class or class alias (see
         * below) along with a value and column. This is handy for when the state method on the
         * factory expects an object as the parameter(s). These config values will be used as the
         * separators to split the class/alias from the id and column.
         *
         * eg:
         * This will trigger the "first" eloquent method to execute with a "where"
         * for the user with an id of 100. This User will then be passed to the
         * "createdBy" method on the factory.
         * $state = [
         *     'createdBy' => [
         *         ['user@100:id'],
         *     ]
         * ]
         */
        'state_separator' => '@',
        'model_separator' => ':',

        /**
         * You can optionally register aliases for models or other objects, rather than having
         * to provide the fully namespaced class name. You can then provide the alias
         * when creating entities via the factory endpoint. You can also define here a function
         * to instruct Laravel-Playwright how to construct an object with the parameters sent
         * from your Playwright test suite.
         */
        'aliases' => [
            // 'user' => 'App\\Models\\User',
            // 'carbon' => fn($date) => \Carbon\Carbon::create($date),
        ],

    ],

];
