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
         * When passing state values to the factory, you can include a FQCN or class alias (see
         * below) along with a value and column, or params (if param_alias). This is handy for
         * when the state method on the factory expects an object as the parameter(s). The
         * separator config values defined below will be used as the detectors and
         * separators to split the class/alias from the passed options/params.
         *
         * When passing a model or model alias, you should prefix the model definition with
         * 'model.' (can be changed as desired below) so that we can differentiate it from
         * a param alias.
         *
         * ** To fetch a model and pass to the state method: **
         *
         * This will trigger the "first" eloquent method to execute with a "where" clause for
         * the user with an `id` of 100. This fetched and hydrated `User` will then be passed
         * to your "createdBy" method on the defined factory.
         *
         * $state = [
         *     'createdBy' => [
         *         ['model.User:100,id'],
         *     ],
         * ]
         *
         * Alternatively you could have passed the FQCN rather than the alias and left off the
         * 'id' column because that's the default. As follows:
         *
         * $state = [
         *     'createdBy' => [
         *         ['model.\\App\\Models\\User:100'],
         *     ],
         * ]
         *
         * ** To construct an object with params to be passed to the state method: **
         *
         * This will use a `param_alias` (defined below) to resolve the param for the `endsAt`
         * state method on the factory as a Carbon instance with the value to the right of the
         * separator. You can also pass multiple parameters to the state method that resolve using
         * aliases as defined below. Parameters for the param_alias should be wrapped in
         * parentheses as shown below.
         *
         * This example will use the `Carbon` alias (defined below - commented out) to make a
         * `Carbon` instance with the value `2023-12-25 23:59:59`, and will be passed to the
         * `endsAt` method on the factory:
         * $state = [
         *     'endsAt' => [
         *         ['Carbon(2023-12-25 23:59:59)'],
         *     ],
         * ]
         *
         * This example will use the Carbon alias to make 2 instances, each with the date values
         * as shown, and both instances will then be passed, in the order they are defined, to the `liveBetween` method on the factory class:
         * $state = [
         *     'liveBetween' => [
         *         ['Carbon(2023-01-01 00:00:00)', 'Carbon(2023-12-25 23:59:59)'],
         *     ],
         * ]
         *
         * This example will use the collect alias to make a Collection with 2 items as defined.
         * The Collection instance will then be passed to the comments method on the factory:
         * $state = [
         *     'comments' => [
         *         ['collect(hello,goodbye)'],
         *     ],
         * ]
         */

        /**
         * Used to tell the package you want a model created, either from the passed FQCN or
         * from the model_alias as defined below.
         */
        'model_designator' => 'model.',

        /**
         * Used to separate the model from any other passed options.
         */
        'model_separator' => ':',

        /**
         * If you wish to resolve a model from the DB, you can optionally pass the column to compare
         * the value to in a where clause. The default column used is `id`. Use this separator to
         * separate the desired column from the rest of the passed options.
         */
        'column_separator' => ',',

        /**
         * If passing multiple params to be passed into an alias' callable, you can separate
         * them using this option.
         */
        'param_separator' => ',',

        /**
         * You can optionally register aliases for models rather than having to provide the fully
         * namespaced class name. You can then provide the alias when creating entities via the
         * factory endpoint.
         */
        'model_aliases' => [
            // 'User' => '\App\Models\User::class',
            // 'Post' => '\App\Models\Post::class',
        ],

    ],

];
