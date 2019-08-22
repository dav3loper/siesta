<?php

use Faker\Generator as Faker;
use siesta\infrastructure\vote\persistence\EloquentVoteRecorder;

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


$factory->define(EloquentVoteRecorder::class, function (Faker $faker) {
    return [
        'movie_id' => $faker->numberBetween(1, 2400),
        'votes' => '[
                    {"userId":' . $faker->numberBetween(1, 5000) . ',"score":' . $faker->numberBetween(0, 2) . '},
                    {"userId":' . $faker->numberBetween(1, 5000) . ',"score":' . $faker->numberBetween(0, 2) . '}
                    ]',
        'historic_votes' => '{}'
    ];
});