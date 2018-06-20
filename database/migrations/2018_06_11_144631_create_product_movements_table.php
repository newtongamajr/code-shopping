<?php

use CodeShopping\Models\ProductMovement;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMovementsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_movements', function (Blueprint $table) {
      $table->increments('id');
      $table->string('movement_type',10);
      $table->unsignedInteger('amount');
      $table->unsignedInteger('product_id');
      $table->foreign('product_id')->references('id')->on('products');
      $table->timestamp(ProductMovement::CREATED_AT)->nullable();
      $table->timestamp(ProductMovement::UPDATED_AT)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('product_movements');
  }
}
