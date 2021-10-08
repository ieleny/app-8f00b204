<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Http\Requests\ProductsRequest;

class ProductsController extends Controller
{
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
        $product = new ProductsModel;
        $product->products_name = $request->name;
        $product->products_quantity = is_null($request->quantity) ? 0 : $request->quantity;
        $product->products_sku = $product->generateSku();
        $product->save();

        return response()->json([
            "message" => "Produto foi salvo com sucesso!"
        ], 201);
    }

    public function updateProduct()
    {

    }

    public function updateQuantity()
    {

    }
}
