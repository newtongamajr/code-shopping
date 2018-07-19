<?php

namespace CodeShopping\Providers;

use CodeShopping\Http\Requests\CategoryRequest;
use CodeShopping\Models\Category;
use CodeShopping\Models\Product;
use CodeShopping\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
  /**
   * This namespace is applied to your controller routes.
   *
   * In addition, it is set as the URL generator's root namespace.
   *
   * @var string
   */
  protected $namespace = 'CodeShopping\Http\Controllers';

  /**
   * Define your route model bindings, pattern filters, etc.
   *
   * @return void
   */
  public function boot()
  {
    //
    parent::boot();

    // Implementação para permitir acesso tanto pelo 'id' da categoria como pelo seu respectivo 'slug'
    Route::bind('category', function ($value)
    {
      /** @var Collection $collection */
      $collection = Category::whereId($value)->orWhere('slug',$value)->get();
      return $collection->first();
    });
    // Implementação para permitir acesso tanto pelo 'id' do produto como pelo seu respectivo 'slug'
    Route::bind('product', function ($value)
    {
      $query = Product::query();
      $query = $this->onlyTrashedIfRequested($query);
      /** @var Collection $collection */
      $collection = $query->whereId($value)->orWhere('slug',$value)->get();
      return $collection->first();
    });

    // Implementação para usuários para permitir acesso também aos usuários deletados...
    Route::bind('user', function ($value)
    {
      $query = User::query();
      $query = $this->onlyTrashedIfRequested($query);
      /** @var Collection $collection */
      $collection = $query->whereId($value)->get();
      return $collection->first();
    });
  }

  /**
   * Permitir acesso a recursos que tenham sofrido 'soft delete'.
   * @param Builder $query
   * @return Builder
   */
  private function  onlyTrashedIfRequested(Builder $query)
  {
    if (\Request::get('excluidos') == 1)
    {
      $query = $query->onlyTrashed();
    }

    return $query;
  }

  /**
   * Define the routes for the application.
   *
   * @return void
   */
  public function map()
  {
    $this->mapApiRoutes();

    $this->mapWebRoutes();

    //
  }

  /**
   * Define the "web" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapWebRoutes()
  {
    Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/web.php'));
  }

  /**
   * Define the "api" routes for the application.
   *
   * These routes are typically stateless.
   *
   * @return void
   */
  protected function mapApiRoutes()
  {
    Route::prefix('api')
      ->middleware('api')
      ->namespace($this->namespace)
      ->group(base_path('routes/api.php'));
  }
}
