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
         * You can optionally register aliases for your domain models rather than having
         * to provide the fully namespaced class name, you can then provide the alias
         * when creating entities via the factory endpoint.
         */
        'models' => [
            // 'user' => 'App\\Models\\User',
        ],

    ],

];
