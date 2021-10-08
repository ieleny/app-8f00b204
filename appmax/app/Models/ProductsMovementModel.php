<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProductsModel;

class ProductsMovementModel extends Model
{
    use HasFactory;

    protected $table = 'products_movement';
    protected $fillable = [
        'products_id',
        'products_sku',
        'products_movement_quantity'
    ];

    public function saveProductMovement()
    {
        
    }

    public function products()
    {
        return $this->hasMany(ProductsModel::class, 'products_id');
    }
}
