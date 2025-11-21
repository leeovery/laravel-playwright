# Laravel Playwright

[![Latest Version on Packagist](https://img.shields.io/packagist/v/leeovery/laravel-playwright.svg?style=flat-square)](https://packagist.org/packages/leeovery/laravel-playwright)
[![Total Downloads](https://img.shields.io/packagist/dt/leeovery/laravel-playwright.svg?style=flat-square)](https://packagist.org/packages/leeovery/laravel-playwright)
![GitHub Actions](https://github.com/leeovery/laravel-playwright/actions/workflows/main.yml/badge.svg)

Backend utilities for Playwright e2e tests. Provides HTTP endpoints and commands to control your Laravel application during testing - manage databases, factories, authentication, migrations, and environment configuration from your Playwright tests.

## Requirements

- PHP 8.3+
- Laravel 11+

## Installation

```bash
composer require leeovery/laravel-playwright --dev
```

Publish config and register service provider:

```bash
php artisan playwright:install
```

## Configuration

The package config (`config/playwright.php`) includes:

- **Environments**: Control which environments expose the endpoints (default: `local,testing,playwright`)
- **Route prefix**: Customize the endpoint URL prefix (default: `__playwright__`)
- **Middleware**: Set middleware for routes (default: `web`)
- **Environment files**: Configure `.playwright.env` file handling
- **Factory settings**: Map model aliases and configure factory behaviors

Create a `.playwright.env` file for test-specific environment variables.

## Features

### HTTP Endpoints

All endpoints are prefixed with `__playwright__` (configurable) and protected by environment checks:

#### Database Management

- `POST /create-database` - Create test database
- `POST /drop-database` - Drop test database
- `POST /migrate` - Run migrations (supports `?fresh=1&seed=1`)
- `POST /truncate` - Truncate specific tables

#### Factory & Authentication

- `POST /factory` - Create model instances via factories
- `POST /login` - Authenticate user (create or find existing)
- `POST /logout` - End session
- `POST /user` - Get current authenticated user

#### Utilities

- `POST /artisan` - Execute artisan commands
- `POST /routes` - Get application route list
- `GET /csrf` - Get CSRF token
- `POST /env-setup` - Swap to `.playwright.env`
- `POST /env-teardown` - Restore original `.env`

### Artisan Commands

```bash
# Setup/teardown test environment
php artisan playwright:env-setup
php artisan playwright:env-teardown

# Database operations
php artisan db:create --database=playwright_test
php artisan db:drop --database=playwright_test
```

## Usage Example

From Playwright tests, interact with Laravel via HTTP:

```javascript
// Setup environment
await request.post("http://localhost/__playwright__/env-setup");

// Run migrations
await request.post("http://localhost/__playwright__/migrate", {
  data: { fresh: true, seed: true },
});

// Create and login user
const user = await request.post("http://localhost/__playwright__/factory", {
  data: {
    model: "User",
    state: ["verified"],
    attributes: { email: "test@example.com" },
  },
});

await request.post("http://localhost/__playwright__/login", {
  data: { attributes: { email: "test@example.com" } },
});

// Teardown
await request.post("http://localhost/__playwright__/env-teardown");
```

### Factory State Parameters

Pass complex state parameters to factories:

```javascript
// Fetch model and pass to state method
data: {
  state: [
    { createdBy: ["model.User:100"] }, // Fetch User with id=100
  ];
}

// Use param aliases (configure in config/playwright.php)
data: {
  state: [{ endsAt: ["Carbon(2023-12-25 23:59:59)"] }];
}
```

Register param aliases in your service provider:

```php
use Leeovery\LaravelPlaywright\Playwright;

Playwright::paramAlias('Carbon', fn($date) => new Carbon($date));
```

## Security

**Important**: This package exposes powerful endpoints that can manipulate your application. Only enable in non-production environments. The package includes middleware that blocks requests outside configured environments.

If you discover security issues, email me@leeovery.com.

## Testing

```bash
composer test
composer test-coverage
```

## Development

```bash
# Format code with Pint
composer pint

# Refactor code with Rector
composer rector
```

## Credits

- [Lee Overy](https://github.com/leeovery)
- [All Contributors](../../contributors)

## License

MIT License. See [License File](LICENSE.md) for details.
