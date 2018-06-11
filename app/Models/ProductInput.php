<?php

namespace CodeShopping\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInput extends Model
{
  protected $fillable = ['amount', 'product_id'];

  const CREATED_AT = 'dtCriacao';
  const UPDATED_AT = 'dtAtualizacao';
}
