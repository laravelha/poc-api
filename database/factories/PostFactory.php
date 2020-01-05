<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->text(150),
        'content' => $faker->text(),
        'published_at' => $faker->date('d/m/Y'),
        'category_id' => factory(App\Category::class),
    ];
});
