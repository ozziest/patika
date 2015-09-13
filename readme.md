## Patika 

[![Build Status](https://travis-ci.org/ozziest/patika.svg)](https://travis-ci.org/ozziest/patika)
[![Total Downloads](https://poser.pugx.org/ozziest/patika/d/total.svg)](https://packagist.org/packages/ozziest/patika)
[![Latest Stable Version](https://poser.pugx.org/ozziest/patika/v/stable.svg)](https://packagist.org/packages/ozziest/patika)
[![Latest Unstable Version](https://poser.pugx.org/ozziest/patika/v/unstable.svg)](https://packagist.org/packages/ozziest/patika.org/packages/laravel/framework)
[![License](https://poser.pugx.org/ozziest/patika/license.svg)](https://packagist.org/packages/ozziest/patika)

Patika is a simple routing package that you can use easily your projects. This is small ans useful package because you dont have to define all routes. You should code your controller instead of routing defination.

#### Installation 

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "ozziest/patika": "dev-master"
    }
}
```

```
$ composer update
```

#### Usage

First of all, you should define `.htaccess` file so that handling all request and send it to `index.php` file. 

`.htaccess`
```
RewriteEngine on
RewriteCond $1 !^(index\.php|images|robots\.txt)
RewriteRule ^(.*)$ /index.php/$1 [L]
```

`Index.php` file must be defined like this;

`index.php`
```php 
// Including composer autoload file
include 'vendor/autoload.php';

// First of all, you should use try-catch block for handling routing errors
try {
    // You must create a new instance of Manager Class with the app argument.
    $patika = new Ozziest\Patika\Manager(['app' => 'App\Controllers']);
    // And calling the route!
    $patika->call();
} catch (Ozziest\Patika\Exceptions\PatikaException $e) {
    // If the controller or method aren't found, you can handle the error.
    echo $e->getMessage();
}
```

That's all! **Patika Router** is active now. Now, you can define your controller which what you want.


`Users.php`
```php
namespace App\Controllers;

class Users {

    /**
     * All
     *
     * @return null
     */
    public function all()
    {
        echo 'App\Controllers\Users@all()';
    }
    
}
```

#### Checking

```
$ php -S localhost:8000 index.php
$ curl -X GET localhost:8000/users/all 
```

#### Documentation

Documentation will be defined soon!