<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsModel extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $fillable = [
        'products_sku', 
        'products_quantity',
        'logs_action'
    ];

}
