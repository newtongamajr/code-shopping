<?php

namespace CodeShopping\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use Sluggable;
  protected $fillable = ['name','active'];

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

  public function products()
  {
    return $this->belongsToMany(Product::class)
                ->using(CategoryProduct::class)
                ->as('CategProd')
                ->withPivot('coluna_exemplo')
                ->withTimestamps(CategoryProduct::CREATED_AT, CategoryProduct::UPDATED_AT)
                ->orderBy('product_id');
  }
}
