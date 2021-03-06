
# Laravel Addomnipot

[![Build Status](https://travis-ci.org/jumilla/laravel-addomnipot.svg)](https://travis-ci.org/jumilla/laravel-addomnipot)
[![Quality Score](https://img.shields.io/scrutinizer/g/jumilla/laravel-addomnipot.svg?style=flat)](https://scrutinizer-ci.com/g/jumilla/laravel-addomnipot)
[![Code Coverage](https://scrutinizer-ci.com/g/jumilla/laravel-addomnipot/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jumilla/laravel-addomnipot)
[![Latest Stable Version](https://poser.pugx.org/laravel-plus/addomnipot/v/stable.svg)](https://packagist.org/packages/laravel-plus/addomnipot)
[![Total Downloads](https://poser.pugx.org/laravel-plus/addomnipot/d/total.svg)](https://packagist.org/packages/laravel-plus/addomnipot)
[![Software License](https://poser.pugx.org/laravel-plus/addomnipot/license.svg)](https://packagist.org/packages/laravel-plus/addomnipot)

[日本語ドキュメント - Japanese](readme-ja.md)

## Features

* Additional add-on features
	* It is a package feature in the application. You can use the image to replicate the directory structure of Laravel 5.
	* By default, it will be placed under the 'addons' directory.
	* You can make one have its own name space (PSR-4) to add-on.
	* It can serve as a package of Laravel 5. Valid namespace notation `{addon-name}::` can be used 'lang', 'view'. Also can use 'config'.
	* Only additional add-on to copy the directory. You do not need to add code to the configuration file, such as 'config/app.php'.
	* 9 types of stationery and offers 2 types of sample. Can be generated by artisan command `php artisan make:addon`.

* Solution of a facade problem in the namespace
	* A facade can be used in the class with a name space under the app directory. (A backslash and a use declaration, unnecessary)
	* A facade can also be handled by the same description method from the inside in add-on name space.

## How to install

### [A] Download the Laravel stationery project

Note: Pacakge Discovery supported.

It is a way to use a model project already to incorporate the [Laravel Extension Pack](https://github.com/jumilla/laravel-extension).

In addition to the [Laravel Addomnipot](https://github.com/jumilla/laravel-addomnipot) of add-on features, and to the [Laravel Versionia](https://github.com/jumilla/laravel-versionia) of semantic version-based migration feature, you can also use source code generator corresponding to the add-on.

```sh
composer create-project laravel-plus/laravel5 <project-name>
```

### [B] Install the `laravel-plus/extension` to an existing project.

Note: Pacakge Discovery supported.

It is a way to use [Laravel Extension Pack](https://github.com/jumilla/laravel-extension).

In addition to the [Laravel Addomnipot](https://github.com/jumilla/laravel-addomnipot) of add-on features, and to the [Laravel Versionia](https://github.com/jumilla/laravel-versionia) of semantic version-based migration feature, you can also use source code generator corresponding to the add-on.

For more information, please refer to the [Laravel Extension Pack](https://github.com/jumilla/laravel-extension) of the document.

### [C] Install the `jumilla/laravel-addomnipot` to an existing project.

Note: Pacakge Discovery supported.

It is a way to incorporate the only [Laravel Addomnipot](https://github.com/jumilla/laravel-addomnipot) of add-on features.

#### 1. Add the package `jumilla/laravel-addomnipot` use Composer.

Use composer.

```sh
composer require jumilla/laravel-addomnipot
```

#### 2. Add the service provider.

Edit file `config/app.php`.

```php
	'providers' => [
		Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
		...
		// Add the folloing line.
		Jumilla\Addomnipot\Laravel\ServiceProvider::class,
	],
```

## Check for running

Add-on `wiki` is made as a example.

```sh
php artisan make:addon wiki sample:ui
```

Please confirm the routing setting.

```sh
php artisan route:list
```

A local server is started and accesses `http://localhost:8000/addons/wiki` by a browser.
When a package name is indicated, it's success.

```sh
php artisan serve
```

## Commands

### `addon:list`

List addons.

```sh
php artisan addon:list
```

When `addons` directory file don't exist, it's made.

### `addon:status`

Can check the status of addons.

```sh
php artisan addon:status
```

When `addons` directory file don't exist, it's made.

### `addon:name`

A file in the add-on is scanned and the PHP namespace is changed.

```sh
php artisan addon:name blog Wonderful/Blog
```

When you'd like to confirm the scanned file, please designate `-v` option.

```sh
php artisan addon:name blog Wonderful/Blog -v
```

### `addon:remove`

An add-on is eliminated.

```sh
php artisan addon:remove blog;
```

`addons/blog`  A directory is just eliminated.

### `make:addon`

An add-on is made.
I add on the next command `blog` is generated as PHP name spatial `Blog` using a form of `library`-type, `blog-app` is generated as PHP name spatial `BlogApp` using a form of `ui`-type.

```sh
php artisan make:addon blog library
php artisan make:addon blog-app ui
```

A skeleton can be chosen from 10 kinds.

- **minimum** - Minimum structure.
- **simple** - The simple structure with the directory **views** and the file **Http/route.php**.
- **asset** - Minimum structure.
- **library** - The composition to which a PHP range and a database are offered.
- **api** - The structure for Web API.
- **ui** - The structure for Web UI.
- **ui-sample** - Example of a Web UI.
- **debug** - The add-on in which program testing facility is put. Service provider registration of 'debug-bar' is also included.
- **generator** - Customized for stub files.
- **laravel5** - The directory structure of Laravel 5.
- **laravel5-auth** - The authentication sample included in Laravel 5.

When not designating a form by a command argument, it can be chosen by an interactive mode.

```sh
php artisan make:addon blog
```

PHP namespace can designate `--namespace` by an option.
Please use `\\` or `/` for a namespace separate.

```sh
php artisan make:addon blog --namespace App\\Blog
php artisan make:addon blog --namespace App/Blog
```

## Helper functions

### addon($name = null)

Get the add-on by name.

```php
$addon = addon('blog');
```

If omit the name, it returns the add-on that contains the calling class.
This is equivalent to a `addon(addon_name())`.

```php

namespace Blog\Http\Controllers;

class BlogController
{
	public function index()
	{
		$addon = addon();	// == addon(addon_name())
		Assert::same('blog', $addon->name());
	}
}
```

`LaravelPlus\Extension\Addons\Addon` object retrieved by the `addon()` function, you can access the add-on attributes and resources.

```php
$addon = addon();
$addon->path();				// {$root}/addons/blog
$addon->relativePath();		// addons/blog
$addon->phpNamespace();		// Blog
$addon->config('page.row_limit', 20);
$addon->trans('emails.title');
$addon->transChoice('emails.title', 2);
$addon->view('layouts.default');
$addon->spec('forms.user_register');
```

### addon_name($class)

Get the add-on name from the class name .
The class name must be a fully qualified name that contains the name space.
Get the add-on by name.

```php
$name = addon_name(get_class($this));
$name = addon_name(\Blog\Providers\AddonServiceProvider::class);		// 'blog'
```

If you omit the argument, returns the name of the add-on contains the caller of the class.

```php
<?php

namespace Blog\Http\Controllers;

class PostsController
{
	public function index()
	{
		$name = addon_name();		// 'blog'
	}
}
```

## Facade expansion

Facade of Laravel converts the static method call of class to an instance method invocation, and has been achieved by creating an alias for the facade class in the global namespace.
For Laravel 5 alias loader does not act only in the global name space , to handle the facade from the name space (such as `App`) it must put `\` to the class name  prefix.

```
function index()
{
	return \View::make()
}
```

Or a use declaration is used.

```
use View;

...

function index()
{
	return View::make()
}
```

Laravel Extension has the alias loader which settles a facade in the namespace in the add-on bottom, so a way of Laravel 4.2 formula document mentioning can be used just as it is.

```
function index()
{
	return View::make()
}
```

## Bootstrap behavior

* Require `files` entry in file `addons/{addon-name}/addon.json`.
* Sees `namespace` entry in file `addons/{addon-name}/addon.php` and establishes class automatic threading based on PSR-4 agreement to all directories specified as `directories`.

## Author

古川 文生 / Fumio Furukawa (fumio@jumilla.me)

## License

MIT
