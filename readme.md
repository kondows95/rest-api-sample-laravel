### How to install
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


### If you make a REST API with Laravel, all you need to do is:
```
1. Routing
Add the following code to 'routes/app.php'.
Route::apiResource('items', 'ItemsController');

2. Database setting
$ php artisan make:migration create_items_table
$ php artisan migrate --seed

3. Implementation
Execute the following command and edit the created files.
$ php artisan make:controller ItemsController
$ php artisan make:model Models/Item
$ php artisan make:request Item/StoreItemRequest
$ php artisan make:request Item/IndexItemsRequest
$ php artisan make:request Item/UpdateItemsRequest

4. Testing
Execute the following command and edit the created files.
$ php artisan make:test ItemTest
$ php artisan make:factory ItemFactory
$ ./vendor/bin/phpunit

5. Operation check by cURL
ItemsController::index()
$ curl -X GET http://localhost/api/items

ItemsController::store()
$ curl -X POST http://localhost/api/items -d "category_id=1&name=newName&price=999&image=new.png"
Remember the inserted id (e.g.19)

ItemsController::show()
$ curl -X GET http://localhost/api/items/19

ItemsController::update()
$ curl -X PUT http://localhost/api/categories/19 -d "category_id=2&name=editedName&price=1000&image=edited.png"

ItemsController::destroy()
$ curl -X DELETE http://localhost/api/categories/19
```
