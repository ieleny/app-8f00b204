<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductsMovementModel;

class ProductsMovementController extends Controller
{
    public function saveProductsMovement($product): ProductsMovementModel
    {
        $productsMovement = new ProductsMovementModel;
        $productsMovement->products_id = $product->id;
        $productsMovement->products_movement_quantity = $product->products_quantity;
        $productsMovement->products_sku = $product->products_sku;

        return $productsMovement;
    }
}
