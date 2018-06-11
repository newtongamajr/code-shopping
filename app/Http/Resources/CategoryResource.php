<?php

namespace CodeShopping\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'nome' => $this->name,
      'slug' => $this->slug,
      'ativo' => (bool) $this->active,
      'dtCriacao' => $this->created_at,
      'dtAtualizacao' => $this->updated_at
    ];
  }
}
