<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    protected $fillable = [
        'request_type',
        'module',
        'reference_id',
        'title',
        'description',
        'payload',
        'requested_by',
        'requested_by_name',
        'approved_by',
        'approved_by_name',
        'status',
        'decision_note',
        'decided_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'decided_at' => 'datetime',
    ];
}