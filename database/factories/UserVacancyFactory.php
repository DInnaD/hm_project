<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\user_vacancy;
use Faker\Generator as Faker;

$factory->define(user_vacancy::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 100),
        'vacancy_id' => $faker->numberBetween(1, 20)
    ];
});
