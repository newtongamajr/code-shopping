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
}
