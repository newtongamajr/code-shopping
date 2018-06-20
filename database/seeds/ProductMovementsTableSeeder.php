<?php

use CodeShopping\Models\Product;
use CodeShopping\Models\ProductMovement;
use Illuminate\Database\Seeder;

class ProductMovementsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $products = Product::all();
    factory(ProductMovement::class,100)
      ->states('Entrada')
      ->make()
      ->each(function ($table) use ($products) {
        /** @var \CodeShopping\Models\Product $product Produto  */
        $product = $products->random();
        $table->product_id = $product->id;
        $table->save();
      });
    factory(ProductMovement::class,40)
      ->states('Saída')
      ->make()
      ->each(function ($table) use ($products) {
        /** @var \CodeShopping\Models\Product $product Produto  */
        $product = $products->random();
        $table->product_id = $product->id;
        $table->save();
      });
    }
}
