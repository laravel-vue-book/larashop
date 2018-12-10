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
    
    $password = "aisha";
    $user = factory(\App\User::class)->create([
            'name'     => 'Aisha Angela Almazan',
            'username' => 'aisha',
            'password' => \Hash::make($password),
            'roles'    => json_encode(["ADMIN"]),
    ]);

    $filepath = public_path('storage/category_images_test'); //for tester only
    
    if(!File::exists($filepath)){
        File::makeDirectory($filepath);
    }

    return [
        'name' => $faker->name,
        'slug' => $faker->unique()->slug,
        'image' =>  $faker->image($filepath,100,100, null, false),
        'created_by' => \App\User::where('username', 'aisha')->first()->id
    ];
});
