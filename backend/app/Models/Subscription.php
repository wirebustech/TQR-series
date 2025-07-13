<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_subscription_id',
        'plan_id',
        'plan_type',
        'status',
        'current_period_start',
        'current_period_end',
        'canceled_at',
        'ended_at'
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for canceled subscriptions
     */
    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    /**
     * Scope for expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('current_period_end', '<', now());
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->current_period_end > now();
    }

    /**
     * Check if subscription is canceled
     */
    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->current_period_end < now();
    }

    /**
     * Check if subscription is in trial
     */
    public function isTrialing(): bool
    {
        return $this->status === 'trialing';
    }

    /**
     * Get days remaining in subscription
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return now()->diffInDays($this->current_period_end, false);
    }

    /**
     * Get formatted plan type
     */
    public function getFormattedPlanTypeAttribute(): string
    {
        return ucfirst($this->plan_type);
    }

    /**
     * Get plan price (mock data - in real app, fetch from Stripe)
     */
    public function getPlanPriceAttribute(): float
    {
        $prices = [
            'monthly' => 9.99,
            'yearly' => 99.99
        ];

        return $prices[$this->plan_type] ?? 0;
    }

    /**
     * Get formatted plan price
     */
    public function getFormattedPlanPriceAttribute(): string
    {
        return '$' . number_format($this->plan_price, 2);
    }

    /**
     * Cancel subscription
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'canceled',
            'canceled_at' => now()
        ]);
    }

    /**
     * Reactivate subscription
     */
    public function reactivate(): void
    {
        $this->update([
            'status' => 'active',
            'canceled_at' => null
        ]);
    }
} 