<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$addressBook = require 'addressBook.php';

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Event::class, function(Faker\Generator $faker) use ($addressBook) {
	$beginAt = $faker->dateTimeThisYear;
	$endAt = $faker->dateTimeInInterval($beginAt, "+ 5 days");
	$address = $addressBook[array_rand($addressBook)];
    $lat = '48.8'.mt_rand(0, 99999);
    $lng = '2.3'.mt_rand(0, 99999);
	return [
		'name' => $faker->sentence,
		'description' => $faker->paragraph,
		'address' => $address,
		'begin_at' => $beginAt,
		'end_at' => $endAt,
        'latitude' => $lat,
        'longitude' => $lng,
        'banner' => 'banners/default.jpg',
		'user_id' => function() {
			return factory('App\User')->create()->id;
		}
	];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->sentence,
        'user_id' => function() {
        	return factory('App\User')->create()->id;
        },
        'event_id' => function() {
        	return factory('App\Event')->create()->id;
        }
    ];
});
