<?php

namespace CodeShopping\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryProduct extends Pivot
{
  protected $table = 'category_product';

  protected $fillable = ['coluna_exemplo'];

  const CREATED_AT = 'dtCriacao';
  const UPDATED_AT = 'dtAtualizacao';

}