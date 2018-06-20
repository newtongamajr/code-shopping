<?php

namespace CodeShopping\Observers;

use CodeShopping\Models\ProductMovement;

class ProductMovementObserver
{
  public function creating(ProductMovement $productMovement)
  {
    $product = $productMovement->product;
    if ($productMovement->movement_type === 'Entrada')
      $product->stock += $productMovement->amount;
    elseif ($productMovement->movement_type === 'Saída')
    {
      $product->stock -= $productMovement->amount;
      if ($product->stock < 0)
        throw new \Exception("Estoque de {$product->name} não pode ser menor que zero ({$product->stock}).");
    }
    $product->save();
  }
}