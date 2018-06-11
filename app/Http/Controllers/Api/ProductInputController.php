<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\ProductInputRequest;
use CodeShopping\Http\Resources\ProductInputResource;
use CodeShopping\Models\Product;
use CodeShopping\Models\ProductInput;
use CodeShopping\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductInputController extends Controller
{
  /**
   * Efetua a entreada em estoque do produto em questão.
   *
   * @param  ProductInputRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProductInputRequest $request)
  {
    $product = Product::find($request->product_id);
    $product->stock += $request->amount;
    $product->save();
    return response(new ProductInputResource($product),201);

  }

  /**
   * Display the specified resource.
   *
   * @param  \CodeShopping\Models\ProductInput $productInput
   * @return \Illuminate\Http\Response
   */
  public function show($productId)
  {
    $product = Product::find($productId);
    return response(new ProductInputResource($product));
  }
}
