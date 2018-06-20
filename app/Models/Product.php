<?php

namespace CodeShopping\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use Sluggable;
  protected $fillable = ['name','description','price','active'];

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
