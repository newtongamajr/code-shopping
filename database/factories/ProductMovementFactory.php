<?php

use Faker\Generator as Faker;

$factory->define(CodeShopping\Models\ProductMovement::class, function () {
  return [];
});

$factory->state(CodeShopping\Models\ProductMovement::class,'Entrada', function (Faker $faker) {
  return [
    'amount' => $faker->numberBetween(10,30),
    'movement_type' => 'Entrada',
  ];
});

$factory->state(CodeShopping\Models\ProductMovement::class, 'Saída', function (Faker $faker) {
  return [
    'amount' => $faker->numberBetween(1,3),
    'movement_type' => 'Saída',
  ];
});
