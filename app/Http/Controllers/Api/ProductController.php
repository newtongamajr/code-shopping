<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\ProductRequest;
use CodeShopping\Models\Product;
use Illuminate\Http\Request;
use CodeShopping\Http\Controllers\Controller;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return response(Product::all());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProductRequest $request)
  {
    $product = Product::create($request->all());
    $product->refresh();

    return response($product,201);
  }

  /**
   * Display the specified resource.
   *
   * @param  \CodeShopping\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function show(Product $product)
  {
    return response($product,200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \CodeShopping\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function update(ProductRequest $request, Product $product)
  {
    $product->fill($request->all());
    $product->save();
    $product->refresh();
    return response($product,200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \CodeShopping\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function destroy(Product $product)
  {
    $product->delete();

    return response([],204);
  }
}
