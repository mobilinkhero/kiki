<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AddonService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'category',
        'price',
        'currency',
        'credit_amount',
        'bonus_amount',
        'duration_days',
        'icon',
        'badge',
        'sort_order',
        'is_active',
        'is_featured',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'credit_amount' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($addon) {
            if (empty($addon->slug)) {
                $addon->slug = Str::slug($addon->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get all purchases of this addon
     */
    public function purchases()
    {
        return $this->hasMany(UserAddonPurchase::class);
    }

    /**
     * Get total credits (base + bonus)
     */
    public function getTotalCreditsAttribute()
    {
        return ($this->credit_amount ?? 0) + ($this->bonus_amount ?? 0);
    }

    /**
     * Scope: Active addons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Featured addons
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: By category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: By type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
