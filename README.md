# Laravel Asus Merlin

Establishes an SSH connection with your Merlin router and exposes a useful API.

## Installation

Use composer to install

```bash
composer require xbnz/laravel-asus-router
```

Run the setup command

```bash
php artisan merlin:setup
```

## Usage

```php
public function handle(\XbNz\AsusRouter\Router $router)
{
    $router->wan()->getIpList(); // Illuminate\Collection
    $router->system()->getRsaKeyList(); // Illuminate\Collection
}
```

## Contributing
Pull requests and issues are welcome.

## License
[MIT](./LICENSE.md)