<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApgPaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'apg_transaction_id',
        'transaction_reference_number',
        'action',
        'method',
        'url',
        'request_payload',
        'response_payload',
        'response_code',
        'is_successful',
        'error_message',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'is_successful' => 'boolean',
    ];

    /**
     * Get the transaction that owns the log
     */
    public function transaction()
    {
        return $this->belongsTo(ApgTransaction::class, 'transaction_reference_number', 'transaction_reference_number');
    }
}
