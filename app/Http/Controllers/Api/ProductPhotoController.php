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
    $photos = ProductPhoto::createWithPhotoFiles($product->id, $request->photos);
    return new ProductPhotoCollection($photos,$product);
  }

  /**
   * Display the specified resource.
   *
   * @param  \CodeShopping\Models\ProductPhoto  $productPhoto
   */
  public function show(Product $product, ProductPhoto $photo)
  {
    $this->validateProduct($product, $photo);

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
    $this->validateProduct($product, $photo);
    $photoSubstituted = ProductPhoto::substitutePhotoAndFile($photo,$request->photo);
    return new ProductPhotoResource($photoSubstituted);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \CodeShopping\Models\ProductPhoto  $productPhoto
   */
  public function destroy(Product $product, ProductPhoto $photo)
  {
    $this->validateProduct($product,$photo);
    $photo->deletePhotoAndFile();
    return response([],204);
  }

  /**
   * @param Product $product
   * @param ProductPhoto $photo
   */
  private function validateProduct(Product $product, ProductPhoto $photo): void
  {
    if ($photo->product_id != $product->id)
      abort(404);
  }
}
