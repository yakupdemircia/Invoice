<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Invoice::class, function (Faker $faker) {

    $amount = $faker->randomFloat(2,100,100000);

    return [
        'due_date' => $faker->dateTime,
        'amount' => $amount,
        'paid' => 0,
        'left' => $amount,
        'order_no' => $faker->randomNumber(),
        'payment_status' => 1,
        'client_id' => function(){
            return factory(App\Client::class)->create()->id;
        }
    ];
});
