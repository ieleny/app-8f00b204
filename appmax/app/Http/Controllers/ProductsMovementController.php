<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductsMovementModel;
use App\Models\ProductsModel;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\LogsController;

class ProductsMovementController extends Controller
{
    protected $logsController;

    public function __construct(
        LogsController $logsController
    )
    {
       $this->logsController = $logsController;
    }

    public function list()
    {
        return ProductsMovementModel::orderBy('products_sku')->get();
    }

    public function getProductsMovement($sku)
    {
        if (ProductsMovementModel::where('products_sku', $sku)->exists()) {
            $product = ProductsMovementModel::where('products_sku', $sku)
                        ->orderBy('updated_at', 'desc')
                        ->get()->toJson(JSON_PRETTY_PRINT);
            return response($product, 200);
        } else {
            return response()->json([
              "message" => "Movimentacoes não encontrada!"
            ], 404);
        }
    }

    public function movementStock($id, Request $request)
    {   
        $product = ProductsModel::find($id);
        $mensagemValidation = $this->validateUpdate($id, $request, $product);

        if($mensagemValidation == 'success')
        {   
            if(!is_null($request->quantity) && !is_null($request->type_movimentacion)){
                $product->products_quantity = $this->quantityStock(
                    $request->quantity, 
                    $product->products_quantity,
                    $request->type_movimentacion
                );
        
            }else {
                $product->products_quantity = $product->products_quantity;
            }

            $product->save();

            $this->saveHistoric($product, 'UPDATELOG');

            return response()->json([
                "mensagem" => "Produto foi atualizado com sucesso!"
            ], 201);
        }

        return response()->json([
            "mensagem" => $mensagemValidation
        ], 404);;
    }

    private function validateUpdate($id, $request, $product): string
    {
        $mensagem = 'success';
        $validation = false;

        if(!ProductsModel::where('id', $id)->exists()){
            $mensagem = "Produto nao encontrado!";
            $validation = true;
        }

        if(!is_null($request->quantity) && is_null($request->type_movimentacion) && $validation == false){
            $mensagem = "É preciso informar qual tipo de operação no estoque, ADICAO=A ou REMOCAO=R!";
        }

        if(!is_null($request->quantity) && !is_null($request->type_movimentacion) && $validation == false){
            $verifiedQuantity = $this->verifiedQuantity(
                                    $request->quantity, 
                                    $product->products_quantity,
                                    $request->type_movimentacion
                                );

            if($verifiedQuantity !== 'success'){
                return $verifiedQuantity;
            } 

        }

        return $mensagem;
    }

    private function verifiedQuantity($quantityRequest, $quantityDataBase, $typeMovimentacion): string
    {   
        $mensagem = 'success';
        $isNumeric = is_numeric($quantityRequest);

        if(!$isNumeric)
        {
            $mensagem = "A Quantidade precisa ser um decimal ou inteiro!";

        }else if($isNumeric && !is_null($quantityRequest)){

            $quantity = $this->quantityStock(
                        $quantityRequest, 
                        $quantityDataBase, 
                        $typeMovimentacion
            );

            if($quantity < 0)
            {
                $mensagem =  "O estoque nao pode ficar negativo!";
            }             
        }
        
        return $mensagem;
    }

    private function quantityStock($quantityRequest, $quantityDataBase, $typeMovimentacion): float
    {
        $quantity = 0.0;

        if($typeMovimentacion == 'A')
        {   
            $quantity = (float)$quantityRequest + (float)$quantityDataBase;
        } 
        else if($typeMovimentacion == 'R')
        {
            $quantity =  (float)$quantityDataBase - (float)$quantityRequest;
        }

        return (float)$quantity;
    }

    private function saveHistoric($product, $keyLog)
    {
        $productsMovement = $this->saveProductsMovement($product);

        $product->productsMovement()->associate($productsMovement);
        $productsMovement->save();

        $logs = $this->logsController
                ->saveLog($product, $keyLog);
        $logs->save();
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
