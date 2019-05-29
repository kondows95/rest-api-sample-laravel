## How to install  
$ cd [YOUR PROJECT FOLDER]　
$ git clone https://github.com/kondows95/laravel-spa-api-startup-kit.git laravel  
$ cd laravel  

$ cp .env.example .env  
$ cat .env | grep -e "APP_CODE_PATH_HOST=" -e 
"DATA_PATH_HOST=" -e "MYSQL_ROOT_PASSWORD=" -e "MYSQL_VERSION="  
>Check the setting of ".env" and edit it if necessary
APP_CODE_PATH_HOST=../  
DATA_PATH_HOST=~/.laradock/data  
MYSQL_VERSION=5.7  
MYSQL_ROOT_PASSWORD=root  

$ composer install  
$ php artisan key:generate  
