# Yet Another Laravel Caching Service

Yet Another Laravel Caching, a.k.a., YACache is a package to extend Laravel 5's base caching
functionality.
This yet another chaching service implementation enables full support of Eloquent ORM caching,
i.e., you can cache Eloquent models without loss of relationship imformations.

## Installation

First, you must run the following script to install the PECL msgpack package:

    $./install.sh

Then, install the package via composer:

    $./composer.sh

## Usage

First, add the yet another caching service provider and facade to your config/app.php file:

    "Illuminate\YetAnother\Cache\CacheServiceProvider",
    "Illuminate\YetAnother\Support\Facades\Cache"

It's important to note that this cachin service will automatically re-bind the Model
class that Eloquent uses for many-to-many relationships. You should now be good to go with
your models.