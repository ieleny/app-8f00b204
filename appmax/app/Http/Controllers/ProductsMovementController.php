<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductsMovementModel;

class ProductsMovementController extends Controller
{
    public function list()
    {
        return ProductsMovementModel::orderBy('products_sku')->get();
    }

    public function getProduct($id)
    {
        if (ProductsMovementModel::where('products_sku', $id)->exists()) {
            $product = ProductsMovementModel::where('products_sku', $id)
                       ->get()->toJson(JSON_PRETTY_PRINT);
            return response($product, 200);
        } else {
            return response()->json([
              "message" => "Produto nao encontrado!"
            ], 404);
        }
    }

    public function saveProductsMovement($product): ProductsMovementModel
    {
        $productsMovement = new ProductsMovementModel;
        $productsMovement->products_id = $product->id;
        $productsMovement->products_movement_quantity = $product->products_quantity;
        $productsMovement->products_sku = $product->products_sku;

        return $productsMovement;
    }
}
