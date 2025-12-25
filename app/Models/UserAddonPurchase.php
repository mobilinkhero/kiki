<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddonPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'addon_service_id',
        'invoice_id',
        'amount_paid',
        'credits_received',
        'bonus_received',
        'status',
        'activated_at',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'credits_received' => 'decimal:2',
        'bonus_received' => 'decimal:2',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the addon service
     */
    public function addonService()
    {
        return $this->belongsTo(AddonService::class);
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Check if purchase is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if purchase is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if purchase has expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Scope: Completed purchases
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Pending purchases
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: For specific user and tenant
     */
    public function scopeForUser($query, $userId, $tenantId)
    {
        return $query->where('user_id', $userId)
            ->where('tenant_id', $tenantId);
    }
}
