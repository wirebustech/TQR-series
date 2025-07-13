<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'payment_method',
        'payment_intent_id',
        'status',
        'metadata',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime'
    ];

    /**
     * Get the user that owns the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the webinar associated with this payment (if applicable)
     */
    public function webinar()
    {
        if (isset($this->metadata['webinar_id'])) {
            return Webinar::find($this->metadata['webinar_id']);
        }
        return null;
    }

    /**
     * Get the donation associated with this payment (if applicable)
     */
    public function donation()
    {
        if (isset($this->metadata['donation_id'])) {
            return SupportDonation::find($this->metadata['donation_id']);
        }
        return null;
    }

    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for payments by type
     */
    public function scopeByType($query, $type)
    {
        return $query->whereJsonContains('metadata->type', $type);
    }

    /**
     * Get payment type from metadata
     */
    public function getPaymentTypeAttribute()
    {
        return $this->metadata['type'] ?? null;
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        $symbols = [
            'usd' => '$',
            'eur' => '€',
            'gbp' => '£'
        ];

        $symbol = $symbols[$this->currency] ?? $this->currency;
        return $symbol . number_format($this->amount, 2);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now()
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
            'processed_at' => now()
        ]);
    }

    /**
     * Get payment description
     */
    public function getDescriptionAttribute(): string
    {
        $type = $this->payment_type;
        
        switch ($type) {
            case 'webinar_registration':
                $webinar = $this->webinar;
                return $webinar ? "Webinar Registration: {$webinar->title}" : 'Webinar Registration';
            
            case 'donation':
                return 'Donation to TQRS';
            
            case 'subscription':
                return 'Premium Subscription';
            
            default:
                return 'Payment';
        }
    }
} 