<?php

namespace App\Http\Controllers;

use App\Models\ProductsModel;

use App\Http\Controllers\ProductsMovementController;
use App\Http\Controllers\LogsController;

use App\Http\Requests\ProductsRequest;

class ProductsController extends Controller
{
    protected $productsMovementController;
    protected $logsController;

    public function __construct(
        ProductsMovementController $productsMovementController,
        LogsController $logsController
    )
    {
       $this->productsMovementController = $productsMovementController;
       $this->logsController = $logsController;
    }

    public function list()
    {
        return ProductsModel::orderBy('products_name')->get();
    }

    public function getProduct($id)
    {
        if (ProductsModel::where('id', $id)->exists()) {
            $product = ProductsModel::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($product, 200);
        } else {
            return response()->json([
              "message" => "Produto não encontrado!"
            ], 404);
        }
    }

    public function createProduct(ProductsRequest $request)
    {
        $product = $this->saveProducts($request);
        $product->save();

        $productsMovement = $this->productsMovementController
                ->saveProductsMovement($product);

        $product->productsMovement()->associate($productsMovement);
        $productsMovement->save();

        $logs = $this->logsController
                ->saveLog($product, 'PRODUCTSAVE');
        $logs->save();

        return response()->json([
            "message" => "Produto foi salvo com sucesso!"
        ], 201);
    }

    public function saveProducts($request): ProductsModel
    {
        $product = new ProductsModel;
        $product->products_name = $request->name;
        $product->products_quantity = is_null($request->quantity) ? 0 : $request->quantity;
        $product->products_sku = $product->generateSku();
        
        return $product;
    }

    public function updateProduct()
    {

    }

    public function updateQuantity()
    {

    }
}
