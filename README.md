# About

[Shop-Local.site](https://shop-local.site); platform & marketplace for local retailers.

## Contributing

All development should be done locally using [Laravel Herd](https://herd.laravel.com/) or any other local development environment.

Pushes to main are automatically deployed on the [production server](https://shop-local.site/).

### Setup

1. Clone the repo
2. Copy .env.example to .env and configure accordingly (APP_URL, DB credentials, and APP_KEY (run `herd php artisan key:generate`))
3. Install dependencies with `herd composer install`
4. Install node dependencies with `herd php artisan npm:install`
5. Run migrations to initialize your database with `herd php artisan migrate`
6. Publish the assets with `herd php winter:mirror public --relative`
7. Set the current version of Winter CMS for cache busting `herd php artisan winter:version`

> **NOTE**: If using the scheduler; ensure that the scheduler is properly configured by following https://wintercms.com/docs/setup/installation#crontab-setup (add `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1` to the crontab
