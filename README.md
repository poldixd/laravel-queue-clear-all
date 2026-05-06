# Laravel Queue Clear All

Clear all queues configured in Laravel Horizon with a single Artisan command.

This package is intended for local development and non-production environments. Do not install or run it in production.

The package reads the queue names from `config('horizon.environments')`, removes duplicates, and runs Laravel's built-in `queue:clear` command for each queue.

## Installation

Install the package with Composer:

```bash
composer require --dev poldixd/laravel-queue-clear-all
```

Laravel package auto-discovery registers the service provider automatically.

## Usage

Clear every queue configured in Horizon using your default queue connection:

```bash
php artisan queue:clear-all
```

By default, the command uses `config('queue.default')` as the queue connection.

You can also pass a connection explicitly:

```bash
php artisan queue:clear-all redis
```

For every unique queue name found in your Horizon configuration, the package calls:

```bash
php artisan queue:clear CONNECTION --queue=QUEUE
```

For example, if Horizon contains the queues `default`, `emails`, and `notifications`, this package clears each of them once.

## Horizon Configuration

The command supports Horizon queue definitions as strings or arrays:

```php
'environments' => [
    'production' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default', 'emails'],
        ],
        'supervisor-2' => [
            'connection' => 'redis',
            'queue' => 'notifications',
        ],
    ],
],
```

## Testing

Run the test suite with:

```bash
composer test
```
