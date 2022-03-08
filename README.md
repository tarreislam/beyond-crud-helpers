## About beyond-crud-helpers

This package has many helper commands for the [Laravel BEyond CRUD](https://spatie.be/products/laravel-beyond-crud) project by [Spatie](https://spatie.be/)

### Installation

1. `composer require --dev tarre/beyond-crud-helpers`
2. `php artisan vendor:publish --provider=Tarre\\BeyondCrudHelpers\\ServiceProvider`
3. checkout `config/beyond-crud-helper.php` but default is OK


### Usage

`php artisan mmake:` double M 

### Available commands

The command parameter `table` requires `doctrine/dbal` 

**New**
* `mmake:dto {name} {location?} {--table=} {--collection} {--force}`
* `mmake:action {name} {location?} {--construct} {--force}`

**Experimental**
* `mmake:model-getter-migration {model} {location}` _(This is an attempt to create actions for all your getters for a given model)_

**Traditional**

* `mmake:controller {name} {location?} {--invoke} {--api} {--model=} {--force}`
* `mmake:event {name} {location?} {--model=} {--force}`
* `mmake:exception {name} {location?} {--force}`
* `mmake:job {name} {location?} {--model=} {--force}`
* `mmake:listener {name} {location?} {--force}`
* `mmake:model {name} {location?} {--table=} {--force}`
* `mmake:notification {name} {location?} {--model=} {--force}`
* `mmake:observer {name} {location?} {--model=} {--force}`
* `mmake:policy {name} {location?} {--model=} {--force}`
* `mmake:query {name} {location?} {--model=} {--force}`
* `mmake:request {name} {location?} {--force}`
* `mmake:resource {name} {location?} {--collection} {--force}`
* `mmake:rule {name} {location?} {--force}`