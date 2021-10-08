<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;

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
        Route::put('update/{id}', [ProductsController::class, 'update']);
    });
});