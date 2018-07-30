<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::group(['namespace' => 'Api', 'as' => 'api.'], function ()
{
  Route::post('login',['as'=>'login','uses' => 'AuthController@login']);
  Route::post('refresh',['as'=>'refresh','uses' => 'AuthController@refresh']);
  Route::group(['middleware' => ['auth:api','jwt.refresh']], function ()
  {
    Route::post('logout',['as'=>'logout','uses' => 'AuthController@logout']);
    Route::get('whoami',['as'=>'whoami','uses' => 'AuthController@whoAmI']);
    Route::resource('categories','CategoryController', ['except' => ['create','edit']]);
    Route::patch('products/{product}/restore', ['as' => 'products.restore', 'uses' => 'ProductController@restore']);
    Route::resource('products','ProductController', ['except' => ['create','edit']]);
    Route::resource('products.categories','ProductCategoryController',['only' => ['index','store','destroy']]);
    Route::post('products/{product}/photos/{photo}',['as'=>'products.photos.substitute','uses' => 'ProductPhotoController@substitute']);
    Route::resource('products.photos','ProductPhotoController',['only' => ['index','show','store','destroy']]);
    Route::resource('inputs', 'ProductInputController', ['only' => ['index','show','store']]);
    Route::resource('outputs', 'ProductOutputController', ['only' => ['index','show','store']]);
    Route::resource('users','UserController',['except' => ['create','edit']]);
  });

});
