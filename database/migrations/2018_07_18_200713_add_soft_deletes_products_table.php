<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('products', function (Blueprint $table) {
      $table->renameColumn('updated_at','dtAtualizacao');
      $table->renameColumn('created_at', 'dtCriacao');
      $table->softDeletes('dtExclusao');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('products', function (Blueprint $table) {
      $table->renameColumn('dtAtualizacao', 'updated_at');
      $table->renameColumn('dtCriacao', 'created_at');
      $table->dropColumn('dtExclusao');
    });
  }
}
