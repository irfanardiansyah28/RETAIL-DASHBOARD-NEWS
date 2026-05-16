<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskFlag extends Model
{
    protected $fillable = [
        'risk_type',
        'severity',
        'module',
        'reference_id',
        'title',
        'description',
        'user_id',
        'user_name',
        'status',
    ];
}