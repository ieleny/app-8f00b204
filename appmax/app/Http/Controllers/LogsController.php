<?php

namespace App\Http\Controllers;

use App\Models\LogsModel;

class LogsController extends Controller
{

    public function saveLog($product, $action):LogsModel
    {
        $logs = new LogsModel;
        $logs->products_sku = $product->products_sku;
        $logs->products_quantity = $product->products_quantity;
        $logs->logs_action = $action;

        return $logs;
    }
}
