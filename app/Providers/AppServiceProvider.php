<?php

namespace CodeShopping\Providers;

use CodeShopping\Models\ProductMovement;
use CodeShopping\Observers\ProductMovementObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    ProductMovement::observe(ProductMovementObserver::class);
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
