#Laravel 4 Modularizer
---
[https://github.com/sendyhalim/laravel-modularizer](https://github.com/sendyhalim/laravel-modularizer)

This package is still in early development, but it is usable and already tested.

Modularizer is a Laravel 4 plugin. Basically it is an artisan command that creates module and auto validation repository based on given input.
It is inspired by these amazing people:

* [creolab's module](https://github.com/creolab/laravel-modules)
* [JeffreyWay's generator](https://github.com/JeffreyWay/Laravel-4-Generators)

---
###Install
---

Add `"sendy/modularizer": "dev-master"` to your composer.json then run `composer update` in your terminal.

Add `Sendy\Modularizer\ModularizerCommandServiceProvider` and `Sendy\Modularizer\ModularizerModulesServiceProvider` respectively to `app/config/app.php` service providers.


* `Sendy\Modularizer\ModularizerCommandServiceProvider` is for registering commands.
* `Sendy\Modularizer\ModularizerModulesServiceProvider` is for registering active modules(registers route and view namespace of  module).

Last, (with default config)include this to your composer.json for autoloading
```
"psr-0":{
            "Modules": "app/modules"
        }
```

---
###Using it...
---
First make you need to publish the config, fire up your terminal  and type

```
php artisan config:publish sendy/modularizer
```

it will generate modularizer config `app/config/packages/sendy/modularizer/module.php`

inside the config, there's
```
<?php
return [
    'base_path'      => app_path() . '/modules',
    'base_directory' => 'Modules',
    'active'         => [

    ],
];
```

* `base_path` is the path where your module will be created.
* `base_directory` is the base directory inside base path, by default it will make module in `app/modules/Modules`. With this, it's easy to include composer autoloading namespace by using `Modules` as base namespace.
* `active` is an array of active modules, think of it as module registration.

---
##Available Commands
---

####Create a module:
```
php artisan modularizer:create-module <ModuleName>
```
arguments

* Module name

options

* `--path` base path to modules to be created, default to `app/modules`.
* `--basedirectory` base directory of modules, default to `Modules`, with default config modules will be created in `app/modules/Modules` and base namespace will be `Modules`.

####Prepare modularizer, make `Core` for your modules
```
php artisan modularizer:prepare
```
options

* `--path` base path to modules to be created, default to `app/modules`.
* `--basedirectory` base directory of modules, default to `Modules`, with default config `Core` will be created in `app/modules/Modules` and base namespace will be `Modules`.

#### Create repository
```
php artisan modularizer:create-repository <ModelName> <ModuleName>
```
arguments

* Model name
* Module name

options

* `--path` base path to modules to be created, default to `app/modules`.
* `--basedirectory` base directory of modules, default to `Modules`, with default config modules will be created in `app/modules/Modules` and base namespace will be `Modules`.
* `--basenamespace` base namespace, default `Modules`.

#### Create migration for a module
This command takes input and call `php artisan migrate:make` command with path edited to module path hence you need to specify your module name
```
php artisan modularizer:make-migration <MigrationName> <ModuleName>
```
arguments

* Migration name
* Module name

options

* `--path` path for migration file, with default config it will be `app/modules/ModuleName/database/migrations`.
* `--basedirectory` base directory of modules, default to `Modules`
* `--create` specify this if the migration is to create new table.(Same as laravel migration option)
* `--table` specify this if the migration is to modify new table.(Same as laravel migration option)


---
##Examples
---

Creating `Admin` module
```
php artisan modularizer:create-module Admin
```

with default config it will create Admin module in `app/modules/Modules`
```
- Admin
    - Controllers
        - AdminBaseController.php
    - Repositories
        - Read
        - Write
    - RepositoryInterfaces
        - Read
        - Write
    - database
        - migrations
    - views
    - routes.php
```

Admin views is registered with its namespace `admin::view-file`, example I have a view file `Admin/views/layout.blade.php`, to get it you need to use `admin::layout`

Modularizer also comes with autovalidation repository, first you need to publish `Core`(I like to call it so, but you can configure whatever name you like)
```
php artisan modularizer:prepare
```
with default config it will create `Core` in `app/modules/Modules`
```
- Core
    - Repositories
        - Read
            - BasicRepositoryReader.php
        - Write
            - BasicRepositoryWriter.php
    - RepositoryInterfaces
        - Read
            - BasicRepositoryReaderInterface.php
        - Write
            - BasicRepositoryWriterInterface.php
    - Validators
        - Interfaces
            - ValidatorInterface.php

```

All repositories and interfaces that are created by modularizer will automatically extend BasicRepository(Reader/Writer) and its interface will automatically extend BasicRepository(Reader/Writer)Interfaces

Notice `ValidatorInterface.php`, everytime we save(create/update) with a repository, we need to pass a class that implements `ValidatorInterface` to the repository. First let's make a validator for model `User`


```
<?php
namespace Modules\Admin\Validators;

use Modules\Core\Validators\Interfaces\ValidatorInterface;
use Validator;

class UserValidator implements ValidatorInterface
{
    public function validate(array $input)
    {
        // do validation here
        // if success return true
        // else return false
    }
}
```

Now we need a UserRepository, with modularizer we can make it fast

```
php artisan modularizer:create-repository User Admin
```

the above command will make repository(read and write) for model `User` inside `Admin` module. 4 Files will be created for you

* `Admin/RepositoryInterfaces/Read/UserRepositoryReaderInterface`
* `Admin/RepositoryInterfaces/Write/UserRepositoryWriterInterface`
* `Admin/Repositories/Read/UserRepositoryReader`
* `Admin/Repositories/Write/UserRepositoryWriter`

The repository interfaces will be tied with user repositories

```
App::singleton('Modules\Admin\RepositoryInterfaces\Write\UserRepositoryWriterInterface', 'Modules\Admin\Repositories\Write\UserRepositoryWriter')
App::singleton('Modules\Admin\RepositoryInterfaces\Read\UserRepositoryReaderInterface', 'Modules\Admin\Repositories\Read\UserRepositoryReader');
```

That's it! We can use the `UserRepository` now

```
$userValidator = App::make('Modules\Admin\Validators\UserValidator');

$repo = App::make('Modules\Admin\RepositoryInterfaces\Write\UserRepositoryWriterInterface');

if ($repo->create($input, $userValidator))
{
    // success
}
else
{
    // fail
}
```
