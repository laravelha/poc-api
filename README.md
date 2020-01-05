# Laravelha POC
Proof of concept to preset-api and generator --api commands

## How to reproduce?
1. install laravel fresh app
```shell script
composer create-project --prefer-dist laravel/laravel poc-api && cd poc-api
```
2. Install laravelha/preset-api
```shell script
composer require laravelha/preset-api
```
3. Install laravelha/generator
```shell script
composer require laravelha/generator
```

4. Publish laravelha generator config file
```shell script
php artisan vendor:publish --tag=ha-generator
```

## The following generators commands were executed:
```shell script
php artisan ha-generator:crud Category -a -s 'title:string(150), description:text:nullable, published_at:timestamp:nullable'
php artisan ha-generator:crud Post -a -s 'title:string(150), content:text, published_at:timestamp:nullable, category_id:unsignedBigInteger:foreign'
php artisan ha-generator:package news
php artisan ha-generator:crud Article -a -s 'title:string(150), content:text, published_at:timestamp:nullable'
```

## Run migrations and factories
After set database in .env
```shell script
php artisan migrate
```
Run tinker and factory create
```shell script
php artisan tinker

# generate 50 posts and categories
factory(\App\Post::class, 50)->create();  

factory(\Laravelha\News\Models\Article::class, 50)->create();
```

> This project use RequestQueryBuildable see about on [laravelha/suppot](https://github.com/laravelha/support) 

## About the packages
After run `php artisan ha-generator:package` is need publish the package and install via composer to auto discovered or follow this steps:

1 - Add psr4 on composer 
```json
"psr-4": {
    "App\\": "app/",
    "Laravelha\\News\\": "packages/news/"
},
```


2. Add services providers on config/app.php
```php
Laravelha\News\Providers\NewsServiceProvider::class,
Laravelha\News\Providers\RouteServiceProvider::class,
```

3. add folder to get annotations on config/l5-swagger
```php
'annotations' => [
    base_path('app'),
    base_path('packages'),
],
```

4. run `composer dump` and `php artisan config:cache`
