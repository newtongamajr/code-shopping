<?php

use Faker\Generator as Faker;

$factory->define(CodeShopping\Models\Product::class, function (Faker $faker) {
  return [
    'name' => 'Produto ' . $faker->randomNumber(4),
    'description' => $faker->sentence,
    'price' => $faker->numberBetween(100,70000)/100.00,
    'stock' => $faker->numberBetween(5,300),
  ];
});
