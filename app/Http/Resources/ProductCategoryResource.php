<?php

namespace CodeShopping\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
      // Aqui 'resource' deve ser entendido como o recurso em questão na chamada sendo executada. Neste caso,
      // 'resource' é Product...
      'produto' => new ProductResource($this->resource),
      // Como Product possui 'categories' de igual forma podemos invocar isto do 'resource' em questão (Product) ...
      'categorias' => CategoryResource::collection($this->resource->categories),
    ];
  }
}
