# Laravel OpenAPI specification

[![Latest Version on Packagist](https://img.shields.io/packagist/v/allatra-it/laravel-openapi.svg?style=flat-square)](https://packagist.org/packages/allatra-it/laravel-openapi)
[![Total Downloads](https://img.shields.io/packagist/dt/allatra-it/laravel-openapi.svg?style=flat-square)](https://packagist.org/packages/allatra-it/laravel-openapi)

Easy way to generate OpenAPI specification for your Laravel Applications.

## Installation

You can install the package via composer:

```bash
composer require allatra-it/laravel-openapi --dev
```

## Publish the config
Run the following command to publish the package config file:

```bash
php artisan vendor:publish --provider="AllatraIt\LaravelOpenapi\LaravelOpenapiServiceProvider"
```

You should now have a config/openapi.php file that allows you to configure the basics of this package.