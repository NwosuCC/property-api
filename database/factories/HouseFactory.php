<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\House::class, function (Faker $faker) {

  /** @var \Illuminate\Support\Collection $categories */
  $categories = app()->make(App\Category::class)->all();

  return [
    'title' => ($name = title_case($faker->words(2, true))),
    'description' => $faker->sentence,
    'slug' => str_slug($name),
    'category_id' => $categories->random(1)->first()->id
  ];

});

$factory->state(App\House::class, 'deleted_house', function ($faker) {
  return [
    'deleted_at' => Carbon\Carbon::now()->subSecond()
  ];
});
