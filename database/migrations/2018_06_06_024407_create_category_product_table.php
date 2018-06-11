<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryProductTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('category_product', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('category_id');
      $table->foreign('category_id')->references('id')->on('categories');
      $table->unsignedInteger('product_id');
      $table->foreign('product_id')->references('id')->on('products');
      $table->string('coluna_exemplo')->comment('Exemplo de coluna em relacionamento')->nullable();
      $table->timestamp(\CodeShopping\Models\CategoryProduct::CREATED_AT)->nullable();
      $table->timestamp(\CodeShopping\Models\CategoryProduct::UPDATED_AT)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('category_product');
  }
}
