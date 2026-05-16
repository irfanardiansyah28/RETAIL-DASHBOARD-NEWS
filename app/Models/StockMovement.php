<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'store_id',
        'product_id',
        'store_name',
        'product_name',
        'old_quantity',
        'new_quantity',
        'difference',
        'user_id',
        'user_name',
        'notes',
    ];
}