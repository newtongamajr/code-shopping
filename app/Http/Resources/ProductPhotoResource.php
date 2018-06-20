<?php

namespace CodeShopping\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPhotoResource extends JsonResource
{
  private $isCollection;

  /**
   * ProductPhotoResource constructor.
   * @param $resource
   * @param $isCollection
   */
  public function __construct($resource, $isCollection = false)
  {
    parent::__construct($resource);
    $this->isCollection = $isCollection;
  }
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $retorno = [
      'id' => $this->id,
      'URLImagem' => $this->photo_url,
      'dtCriacao' => $this->dtCriacao,
      'dtAtualizacao' => $this->dtAtualizacao,
    ];
    if (!$this->isCollection)
    {
      $retorno['produto'] = new ProductResource($this->product);
    }
    return $retorno;
  }
}
