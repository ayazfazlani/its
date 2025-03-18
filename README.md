# Tallstarter - A Laravel Livewire Starter Kit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mortenebak/tallstarter.svg?style=flat-square)](https://packagist.org/packages/mortenebak/tallstarter)
[![Project Status: Active – The project has reached a stable, usable state and is being actively developed.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active)
![GitHub last commit](https://img.shields.io/github/last-commit/mortenebak/tallstarter)
![GitHub Sponsors](https://img.shields.io/github/sponsors/mortenebak)
<a href="https://herd.laravel.com/new?starter-kit=mortenebak/tallstarter"><img src="https://img.shields.io/badge/Install%20with%20Herd-f55247?logo=laravel&logoColor=white"></a>

This Starter kit contains my starting point when developing a new Laravel project. Its based on the official Livewire Starter kit, and includes the following features:
- ✅ **User Management**, 
- ✅ **Role Management**,
- ✅ **Permissions Management**,
- ✅ **Localization** options
- ✅ Separate **Dashboard for Super Admins**
- ✅ Updated for Laravel 12.0 **and** Livewire 3.0


### Admin dashboard view:
![alt text](docs/backend.png "Backend View")
### Supporting multiple languages:
![alt text](docs/locale.png "Localization View")


## TALL stack

-   [Tailwind CSS](https://tailwindcss.com)
-   [Alpine.js](https://alpinejs.dev)
-   [Laravel](https://laravel.com)
-   [Laravel Livewire](https://livewire.laravel.com) using the components.
-   [Flux UI](https://fluxui.dev) for flexible UI components

## Further it includes:

-   [Laravel Pint](https://github.com/laravel/pint) for code style fixes
-   [PestPHP](https://pestphp.com) for testing
-   [missing-livewire-assertions](https://github.com/christophrumpel/missing-livewire-assertions) for extra testing of Livewire components by [Christoph Rumpel](https://github.com/christophrumpel)
-   [LivewireAlerts](https://github.com/jantinnerezo/livewire-alert) for SweetAlerts
-   [Spatie Roles & Permissions](https://spatie.be/docs/laravel-permission/v5/introduction) for user roles and permissions
-   [Strict Eloquent Models](https://planetscale.com/blog/laravels-safety-mechanisms) for safety
-   [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) for debugging
-   [Laravel IDE helper](https://github.com/barryvdh/laravel-ide-helper) for IDE support

### Upcoming features

-   [Wire Elements / Modals](https://github.com/wire-elements/modal) for modals (still deciding)
-   [Laravel Cashier](https://laravel.com/docs/10.x/billing) for Stripe integration

# Installation

![alt text](docs/bash-install.png "Localization View")

```bash
laravel new my-project --using=mortenebak/tallstarter
```

You could also just use this repository as a starting point for your own project by clicking use template. If installing manually, these are the steps to install:

## 1. Install dependencies

```bash
composer install
npm install
npm run build # or npm run dev
```

## 2. Configure environment

Setup your `.env` file and run the migrations.

```bash
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

## 3. Migration

```bash
php artisan migrate
```

## 4. Seeding

```bash
php artisan db:seed
```

## 5. Creating the first Super Admin user

```bash
php artisan app:create-super-admin
```

## 6. Set default timezone if different from UTC

```php
// config/app.php
return [
    // ...

    'timezone' => 'Europe/Copenhagen' // Default: UTC

    // ...
];
```

# Developing

## Check for code style issues

```bash
composer review
```

This command will run, in order:

-   Laravel/Pint
-   PHPStan
-   Rector (dry-run)
-   PestPHP

Ensuring that your code is up to standard and tested.

# Contributing

Feel free to contribute to this project by submitting a pull request.

# Credits

I'd like to thank all the people who have contributed to the packages used in this project.
Especially [Spatie](https://spatie.be) for their great packages, Livewire and Alpinejs for their awesome framework and the Laravel community for their great work. And of course [Laravel](https://laravel.com) for their awesome framework, and their [Livewire Starter Kit](https://github.com/laravel/livewire-starter-kit), which this kit is based on.

### Contributers
Take a look at the [contributors](https://github.com/mortenebak/tallstarter/graphs/contributors) who have helped make this project better. Many thanks!

# Donate

If you like this project, please consider [donating to support it](https://github.com/sponsors/mortenebak).

Thanks to:
- [Grazulex](https://github.com/Grazulex)
