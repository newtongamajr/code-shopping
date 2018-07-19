<?php

namespace CodeShopping\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use Sluggable, SoftDeletes;

  protected $fillable = ['name','description','price','active'];
  protected $dates = ['dtCriacao','dtAtualizacao','dtExclusao'];
  const CREATED_AT = 'dtCriacao';
  const UPDATED_AT = 'dtAtualizacao';
  const DELETED_AT = 'dtExclusao';

  /**
   * Retorna o array de configuração do sluggable
   *
   * @return array
   *
   */
  public function sluggable(): array
  {
    return ['slug' => ['source' => 'name']];
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class)
                ->using(CategoryProduct::class)
                ->as('CategProd')
                ->withPivot('coluna_exemplo')
                ->withTimestamps(CategoryProduct::CREATED_AT, CategoryProduct::UPDATED_AT)
                ->orderBy('category_id','asc');
  }

  public function productInputs()
  {
    return $this->hasMany(ProductMovement::class);
  }

  public function photos()
  {
    return $this->hasMany(ProductPhoto::class);
  }
}
