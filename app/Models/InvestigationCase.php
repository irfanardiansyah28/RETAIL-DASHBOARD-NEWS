<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestigationCase extends Model
{
    protected $fillable=[
        'risk_flag_id',
        'case_number',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'investigation_note'
    ];

    public function riskFlag()
    {
        return $this->belongsTo(
            RiskFlag::class
        );
    }

    public function investigator()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }
}