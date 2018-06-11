<?php

use CodeShopping\Models\ProductInput;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductInputsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_inputs', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('amount');
      $table->unsignedInteger('product_id');
      $table->foreign('product_id')->references('id')->on('products');
      $table->timestamp(ProductInput::CREATED_AT)->nullable();
      $table->timestamp(ProductInput::UPDATED_AT)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('product_inputs');
  }
}
