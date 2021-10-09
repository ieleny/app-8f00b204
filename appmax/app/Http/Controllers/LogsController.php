<?php

namespace App\Http\Controllers;

use App\Models\LogsModel;

class LogsController extends Controller
{

    public function list()
    {
        return LogsModel::orderBy('products_sku')->get();
    }

    public function getProduct($sku)
    {
        if (LogsModel::where('products_sku', $sku)->exists()) {
            $product = LogsModel::where('products_sku', $sku)->get()->toJson(JSON_PRETTY_PRINT);
            return response($product, 200);
        } else {
            return response()->json([
              "message" => "Produto nao encontrado!"
            ], 404);
        }
    }

    public function saveLog($product, $action):LogsModel
    {
        $logs = new LogsModel;
        $logs->products_sku = $product->products_sku;
        $logs->products_quantity = $product->products_quantity;
        $logs->logs_action = $action;

        return $logs;
    }
}
