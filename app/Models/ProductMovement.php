<?php

namespace CodeShopping\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMovement extends Model
{
  protected $fillable = ['amount', 'product_id', 'movement_type'];

  const CREATED_AT = 'dtCriacao';
  const UPDATED_AT = 'dtAtualizacao';

  public function product()
  {
    return $this->belongsTo(Product::class);
  }
  /**
   * Scope para pesquisar somente as entradas em estoque.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeInput($query)
  {
    return $query->where('movement_type', '=','Entrada');
  }
  /**
   * Scope para pesquisar somente as saídas de estoque.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeOutput($query)
  {
    return $query->where('movement_type', '=','Saída');
  }
}
