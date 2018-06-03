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
}
