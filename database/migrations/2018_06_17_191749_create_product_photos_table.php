<?php

use CodeShopping\Models\ProductPhoto;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPhotosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_photos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('file_name');
      $table->unsignedInteger('product_id');
      $table->foreign('product_id')->references('id')->on('products');
      $table->timestamp(ProductPhoto::CREATED_AT)->nullable();
      $table->timestamp(ProductPhoto::UPDATED_AT)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('product_photos');
  }
}
