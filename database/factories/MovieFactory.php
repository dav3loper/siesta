<?php

use Faker\Generator as Faker;
use siesta\infrastructure\movie\persistence\EloquentMovieRecorder;

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

$factory->define(EloquentMovieRecorder::class, function (Faker $faker) {
    return [
        'id' => 1,
        'title' => $faker->title,
        'poster' => $faker->imageUrl(),
        'duration' => $faker->numberBetween(10, 240),
        'summary' => $faker->text,
        'trailer_id' => $faker->uuid,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,

    ];
});
