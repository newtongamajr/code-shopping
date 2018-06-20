<?php

use CodeShopping\Models\ProductPhoto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // $this->call(UsersTableSeeder::class);
    $this->call(CategoriesTableSeeder::class);
    $this->call(ProductsTableSeeder::class);
    $this->call(ProductMovementsTableSeeder::class);
    $this->call(ProductPhotosTableSeeder::class);
  }
}