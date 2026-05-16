<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RulePerformance extends Model
{
    protected $fillable=[
        'rule_name',
        'trigger_count',
        'high_risk_count',
        'medium_risk_count',
        'low_risk_count'
    ];
}