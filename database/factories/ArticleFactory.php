<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(5),
        'price' => $faker->randomFloat(2, 1, 100),
        'total_in_shelf' => $faker->numberBetween(0, 25),
        'total_in_vault' => $faker->numberBetween(0, 500),
    ];
});
