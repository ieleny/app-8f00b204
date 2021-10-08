<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProductsMovementModel;

class ProductsModel extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = ['products_name', 'products_sku','products_quantity'];

    public function generateSku()
    {
        $unique_no = ProductsModel::orderBy('id', 'DESC')->pluck('id')->first();

        if($unique_no == null or $unique_no == ""){
            #If Table is Empty
            $unique_no = 1;
        }
        else{
            #If Table has Already some Data
            $unique_no = $unique_no + 1;
        }

        return 'PRO'.$unique_no;
    }

    public function productsMovement()
    {
        return $this->belongsTo(ProductsMovementModel::class, 'id');
    }

}
