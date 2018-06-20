<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\ProductInputRequest;
use CodeShopping\Http\Resources\ProductMovementResource;
use CodeShopping\Models\Product;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Models\ProductMovement;
use Illuminate\Http\Request;

class ProductInputController extends Controller
{
  /**
   * Apresenta todas as entradas de estoque de forma paginada.
   *
   */
  public function index()
  {
    $inputs = ProductMovement::with('product')->input()->paginate(10);  // eager loading com o uso do with ao invés de lazy...
    return ProductMovementResource::collection($inputs);
  }

  /**
   * Efetua a entrada em estoque do produto em questão.
   *
   * @param  ProductInputRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProductInputRequest $request)
  {
    $input = ProductMovement::create($request->all()+['movement_type' => 'Entrada']);
    return response(new ProductMovementResource($input),201);
  }

  /**
   * Apresenta uma entrada de estoque específica. Caso a informação solicitada não seja uma entrada,
   * apresentará valores nulos.
   *
   * @param  integer $productInput
   * @return \Illuminate\Http\Response
   */
  public function show(ProductMovement $input)  // Esta variável deve ser sempre o nome colocado no recurso no singular
  {
    if ($input->movement_type !== 'Entrada')
      $input = new ProductMovement();
    return response(new ProductMovementResource($input));
  }
}
