<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->unique()->slug,
        'image' => $faker->image('storage/category_images'),
        'created_by' => \App\User::where('username', 'administrator')->first()->id
    ];
});
