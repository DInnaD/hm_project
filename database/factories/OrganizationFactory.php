<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Organization;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        'title' => $faker->company,
        'country' => $faker->country,
        'city' => $faker->city,
        'user_id' => $faker->numberBetween(1, 100)
    ];
});
