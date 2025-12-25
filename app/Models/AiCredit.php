<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'balance',
        'reserved',
        'total_purchased',
        'total_used',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'reserved' => 'decimal:2',
        'total_purchased' => 'decimal:2',
        'total_used' => 'decimal:2',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions
     */
    public function transactions()
    {
        return $this->hasMany(AiCreditTransaction::class, 'user_id', 'user_id')
            ->where('tenant_id', $this->tenant_id);
    }

    /**
     * Get available balance (balance - reserved)
     */
    public function getAvailableBalanceAttribute()
    {
        return max(0, $this->balance - $this->reserved);
    }

    /**
     * Check if user has enough credits
     */
    public function hasEnoughCredits($amount)
    {
        return $this->available_balance >= $amount;
    }

    /**
     * Reserve credits for a transaction
     */
    public function reserve($amount)
    {
        if (!$this->hasEnoughCredits($amount)) {
            return false;
        }

        $this->reserved += $amount;
        $this->save();

        return true;
    }

    /**
     * Release reserved credits
     */
    public function release($amount)
    {
        $this->reserved = max(0, $this->reserved - $amount);
        $this->save();
    }

    /**
     * Deduct credits (finalize transaction)
     */
    public function deduct($amount, $reserved = 0)
    {
        $this->balance -= $amount;
        $this->total_used += $amount;

        if ($reserved > 0) {
            $this->reserved -= $reserved;
        }

        $this->save();
    }

    /**
     * Add credits
     */
    public function add($amount)
    {
        $this->balance += $amount;
        $this->total_purchased += $amount;
        $this->save();
    }
}
