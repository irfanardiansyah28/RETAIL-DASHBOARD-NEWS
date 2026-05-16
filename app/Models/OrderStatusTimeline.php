<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusTimeline extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'title',
        'description',
        'user_id',
        'user_name',
    ];
}