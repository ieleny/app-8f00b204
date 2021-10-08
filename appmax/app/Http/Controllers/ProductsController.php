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

    public function listProduct()
    {

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
