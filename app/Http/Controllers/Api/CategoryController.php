<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\CategoryRequest;
use CodeShopping\Http\Resources\CategoryResource;
use CodeShopping\Models\Category;
use Illuminate\Http\Request;
use CodeShopping\Http\Controllers\Controller;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $categories = Category::all();
    return response(CategoryResource::collection($categories));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CategoryRequest $request)
  {
    $category = Category::create($request->all());
    $category->refresh();
    return response(new CategoryResource($category));
  }

  /**
   * Display the specified resource.
   *
   * @param  \CodeShopping\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function show(Category $category)
  {
    return response(new CategoryResource($category));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \CodeShopping\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function update(CategoryRequest $request, Category $category)
  {
    $category->fill($request->all());
    $category->save();

    return response(new CategoryResource($category));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \CodeShopping\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function destroy(Category $category)
  {
    $category->delete();
    return response([],204);
  }
}
