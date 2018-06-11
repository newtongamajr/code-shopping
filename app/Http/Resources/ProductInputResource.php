<?php

namespace CodeShopping\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductInputResource extends JsonResource
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
        'descricao' => $this->description,
        'qtdeEstoque' => (int) $this->stock,
        'vlrEstoque' => (float) $this->stock * $this->price,
        'ativo' => (bool) $this->active,
        'dtCriacao' => $this->created_at,
        'dtAtualizacao' => $this->updated_at
      ];
    }
}
