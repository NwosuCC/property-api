<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {

  /** @var User $admin_user */
  $admin_user = User::where('email', UsersTableSeeder::$test_admin)->first();

  return [
    'name' => ($name = title_case($faker->words(2, true))),
    'description' => $faker->sentence,
    'slug' => str_slug($name),
    'user_id' => $admin_user->id,
  ];

});

$factory->state(App\Category::class, 'deleted_category', function ($faker) {
  return [
    'deleted_at' => Carbon\Carbon::now()->subSecond()
  ];
});
