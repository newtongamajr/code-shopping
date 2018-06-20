<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\ProductInputRequest;
use CodeShopping\Http\Requests\ProductOutputRequest;
use CodeShopping\Http\Resources\ProductMovementResource;
use CodeShopping\Models\Product;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Models\ProductMovement;
use Illuminate\Http\Request;

class ProductOutputController extends Controller
{
  /**
   * Apresenta todas as saídas de estoque de forma paginada.
   *
   */
  public function index()
  {
    $outputs = ProductMovement::with('product')->output()->paginate(10);  // eager loading com o uso do with ao invés de lazy...
    return ProductMovementResource::collection($outputs);
  }

  /**
   * Efetua a saída em estoque do produto em questão.
   *
   * @param  ProductInputRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProductOutputRequest $request)
  {
    $output = ProductMovement::create($request->all() + ['movement_type' => 'Saída']);
    return response(new ProductMovementResource($output),201);
  }

  /**
   * Apresenta uma saída de estoque específica. Caso a informação solicitada não seja uma saída de estoque,
   * apresentará valores nulos.
   *
   * @param  integer $productInput
   * @return \Illuminate\Http\Response
   */
  public function show(ProductMovement $output)  // Esta variável deve ser sempre o nome colocado no recurso no singular
  {
    if ($output->movement_type !== 'Saída')
      $output = new ProductMovement();
    return response(new ProductMovementResource($output));
  }
}
