<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScenarioRule extends Model
{
    protected $fillable = [
        'rule_name',
        'module',
        'condition_field',
        'operator',
        'condition_value',
        'risk_type',
        'severity',
        'title',
        'description',
        'is_active',
    ];
}