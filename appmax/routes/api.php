<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductsMovementController;
use App\Http\Controllers\LogsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function(){

    Route::group(['prefix' => 'products'], function() {
        Route::get('list', [ProductsController::class, 'list']);
        Route::get('{id}', [ProductsController::class, 'getProduct']);

        Route::post('create', [ProductsController::class, 'create']);
    });

    Route::group(['prefix' => 'productsMovement'], function() {
        Route::get('list', [ProductsMovementController::class, 'list']);
        Route::get('{sku}', [ProductsMovementController::class, 'getProductsMovement']);

        Route::put('{id}', [ProductsMovementController::class, 'movementStock']);
    });

    Route::group(['prefix' => 'logs'], function() {
        Route::get('list', [LogsController::class, 'list']);
        Route::get('{sku}', [LogsController::class, 'getProduct']);
    });
});