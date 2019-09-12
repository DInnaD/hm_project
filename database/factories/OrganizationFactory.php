<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Organization;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {
    $employers = User::where('role', 'employer')->pluck('id')->toArray();
    return [
        'title' => $faker->company,
        'country' => $faker->country,
        'city' => $faker->city,
        'user_id' => $faker->randomElement($employers)
    ];
});
