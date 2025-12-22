<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApgTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_reference_number',
        'order_id',
        'user_id',
        'tenant_id',
        'amount',
        'currency',
        'transaction_type',
        'related_id',
        'auth_token',
        'apg_transaction_id',
        'account_number',
        'mobile_number',
        'status',
        'response_code',
        'response_description',
        'order_datetime',
        'transaction_datetime',
        'paid_at',
        'request_data',
        'response_data',
        'error_message',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'order_datetime' => 'datetime',
        'transaction_datetime' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get payment logs for this transaction
     */
    public function logs()
    {
        return $this->hasMany(ApgPaymentLog::class, 'transaction_reference_number', 'transaction_reference_number');
    }

    /**
     * Check if transaction is paid
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Check if transaction is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Scope to get paid transactions
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope to get pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
