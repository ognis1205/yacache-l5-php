# Yet Another Laravel Caching Service

Yet Another Laravel Caching, a.k.a., YACache is a package to extend Laravel 5's base caching
functionality.

This yet another chaching service implementation enables full support of Eloquent ORM caching,
i.e., you can cache Eloquent models without loss of relationship imformations.

## Installation

Run the following shell script:

    $ ./composer.sh

## Usage

Just use "yet another" packages where you use corresponding default implementations, e.g.:

    use Illuminate\YetAnother\Support\Facades;

instead of:

    use Illuminate\Support\Facades;