<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\ProductPhotoRequest;
use CodeShopping\Http\Resources\ProductPhotoCollection;
use CodeShopping\Http\Resources\ProductPhotoResource;
use CodeShopping\Http\Resources\ProductResource;
use CodeShopping\Models\Product;
use CodeShopping\Models\ProductPhoto;
use Illuminate\Http\Request;
use CodeShopping\Http\Controllers\Controller;

class ProductPhotoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   */
  public function index(Product $product)
  {
    return new ProductPhotoCollection($product->photos,$product);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   */
  public function store(ProductPhotoRequest $request, Product $product)
  {
    return ProductPhoto::createWithPhotoFiles($product->id, $request->photos);
  }

  /**
   * Display the specified resource.
   *
   * @param  \CodeShopping\Models\ProductPhoto  $productPhoto
   */
  public function show(Product $product, ProductPhoto $photo)
  {
    if ($photo->product_id != $product->id)
      abort(404);

    return new ProductPhotoResource($photo);
  }

  /**
   * Substitui uma determinada foto do produto por outra informada.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \CodeShopping\Models\ProductPhoto  $productPhoto
   */
  public function substitute(ProductPhotoRequest $request, Product $product, ProductPhoto $photo)
  {
    $photoSubstituted = ProductPhoto::substitutePhotoAndFile($photo,$request->photos);
    return new ProductPhotoResource($photoSubstituted);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \CodeShopping\Models\ProductPhoto  $productPhoto
   */
  public function destroy(Product $product, ProductPhoto $photo)
  {
    ProductPhoto::removePhotoAndFile($photo);
    return response([],204);
  }
}
