<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Vacancy;
use Faker\Generator as Faker;

$factory->define(Vacancy::class, function (Faker $faker) {
    return [
        'vacancy_name' => $faker->jobTitle,
        'workers_amount' => $faker->numberBetween(3, 20),
        'salary' => $faker->numberBetween(300, 1500),
        'organization_id' => $faker->numberBetween(1, 20),
    ];
});
