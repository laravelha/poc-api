<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'title' => $faker->text(150),
        'description' => $faker->text(),
        'published_at' => $faker->date('d/m/Y'),
    ];
});
