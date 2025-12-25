<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiUsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'model',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'credits_used',
        'request_data',
    ];

    protected $casts = [
        'credits_used' => 'decimal:2',
        'request_data' => 'array',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: For specific model
     */
    public function scopeModel($query, $model)
    {
        return $query->where('model', $model);
    }

    /**
     * Scope: For specific user
     */
    public function scopeForUser($query, $userId, $tenantId)
    {
        return $query->where('user_id', $userId)
            ->where('tenant_id', $tenantId);
    }

    /**
     * Scope: Today's usage
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope: This month's usage
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}
