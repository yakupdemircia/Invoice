<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Client::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'tel' => $faker->phoneNumber,
        'email' => $faker->email,
        'code' => $faker->word
    ];
});
