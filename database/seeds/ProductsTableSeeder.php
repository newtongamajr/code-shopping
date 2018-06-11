<?php

use CodeShopping\Models\Category;
use CodeShopping\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /** @var \Illuminate\Database\Eloquent\Collection $categorias */
    $categorias = Category::all();
    $faker = Faker::create();
    factory(Product::class,100)
      ->create()
      ->each( function (Product $produto) use ($categorias, $faker){
        $categoryId = $categorias->random()->id;
        $produto->categories()->attach($categoryId,['coluna_exemplo' => $faker->sentence]);
      });
  }
}
