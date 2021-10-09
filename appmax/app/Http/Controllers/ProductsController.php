<?php

namespace App\Http\Controllers;

use App\Models\ProductsModel;

use App\Http\Controllers\ProductsMovementController;
use App\Http\Controllers\LogsController;

use Illuminate\Http\Request;
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

    public function create(ProductsRequest $request)
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
            "menssagem" => "Produto foi salvo com sucesso!"
        ], 201);
    }

    public function update($id, Request $request)
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

            $product->products_name = is_null($request->name) 
                    ? $product->products_name 
                    : $request->name;

            $product->save();

            $productsMovement = $this->productsMovementController
                                ->saveProductsMovement($product);

            $product->productsMovement()->associate($productsMovement);
            $productsMovement->save();

            $logs = $this->logsController
                    ->saveLog($product, 'UPDATESAVE');
            $logs->save();

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
            $mensagem = "Produto não encontrado!";
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
                $mensagem =  "O estoque não pode ficar negativo!";
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

    private function saveProducts($request): ProductsModel
    {
        $product = new ProductsModel;
        $product->products_name = $request->name;
        $product->products_quantity = is_null($request->quantity) ? 0 : $request->quantity;
        $product->products_sku = $product->generateSku();
        
        return $product;
    }

}
