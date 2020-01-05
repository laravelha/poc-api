<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Laravelha\News\Models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->text(150),
        'content' => $faker->text(),
        'published_at' => $faker->date('d/m/Y'),
    ];
});
