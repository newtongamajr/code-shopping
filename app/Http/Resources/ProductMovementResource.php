<?php

namespace CodeShopping\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductMovementResource extends JsonResource
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
        'tipoMovimento' => $this->movement_type,
        'qtdMovimento' => $this->amount,
        'produto' => new ProductResource($this->product),
      ];
    }
}
