<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\ProductCategoryRequest;
use CodeShopping\Http\Resources\ProductCategoryResource;
use CodeShopping\Models\Category;
use CodeShopping\Models\Product;
use Illuminate\Http\Request;
use CodeShopping\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Product $product)
  {
    return response (new ProductCategoryResource($product));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProductCategoryRequest $request, Product $product)
  {
    //Sincronizar as categorias do produto que foram enviadas pela request...
    $alteracoes = $product->categories()->sync($request->categories);
    // Capturar somente as categorias que foram adicionadas após a sincronização...
    $categoriasAlteradas = $alteracoes['attached'];
    // Recuperar do DB as informações das categorias adicionadas
    $categorias = Category::whereIn('id',$categoriasAlteradas)->get();
    // Apresentar status 201, caso de fato categorias foram adicionadas ou 200 indicando que tudo permaneceu como estava...
    // Com esta última implementação de ProductCategoryResource, acho que a resposta da request ficou um pouyco diferente
    // porque não leva mais em conta as categorias alteradas e sim todas as categorias que um dado produto possua, certo?
    return $categorias->count() ? response(new ProductCategoryResource($product),201): response([]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Product $product, Category $category)
  {
    $product->categories()->detach($category->id);
    return response([],204);
  }
}
