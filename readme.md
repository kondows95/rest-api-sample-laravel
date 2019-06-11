## How to install
```
$ cd [YOUR PROJECT FOLDER]
$ git clone https://github.com/kondows95/laravel-spa-api-startup-kit.git laravel
$ cd laravel

$ cp .env.example .env
$ vi .env
DB_HOST=127.0.0.1
DB_DATABASE=sample_db
DB_USERNAME=root
DB_PASSWORD=

$ vi .env.testing
DB_HOST=127.0.0.1
DB_DATABASE=sample_db_testing
DB_USERNAME=root
DB_PASSWORD=

(MySQL5.7 needed)
$ mysql -u root
mysql> CREATE SCHEMA `sample_db` DEFAULT CHARACTER SET utf8;
mysql> CREATE SCHEMA `sample_db_testing` DEFAULT CHARACTER SET utf8;
mysql> quit;

$ composer install
$ php artisan key:generate

$ php artisan migrate --seed
$ php artisan migrate --seed --env=testing

$ ./vendor/bin/phpunit
```


## Basics of Laravel for the API
```
$ php artisan make:controller CategoriesController
$ php artisan make:model Models/Category
$ php artisan make:request Category/StoreCategoryRequest
$ php artisan make:factory CategoryFactory
$ php artisan make:migration create_categories_table

$ php artisan make:controller ItemsController
$ php artisan make:model Models/Item
$ php artisan make:request Item/StoreItemRequest
$ php artisan make:request Item/IndexItemsRequest
$ php artisan make:factory ItemFactory
$ php artisan make:migration create_items_table

$ vi database/seeds/DatabaseSeeder.php
```
Files can be created without using the artisan command. 
However, I recommend creating a file with the artisan command. 
Because, using the artisan command allows even beginners to do responsibility division of MVC.
