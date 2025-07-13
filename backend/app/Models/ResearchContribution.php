<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchContribution extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'abstract',
        'content',
        'type',
        'status',
        'keywords',
        'file_url',
        'file_size',
        'file_type',
        'user_id',
        'reviewed_by',
        'reviewed_at',
        'review_comments',
        'review_score',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
        'review_score' => 'integer',
    ];

    /**
     * Get the user who submitted this contribution.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who reviewed this contribution.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope a query to only include pending contributions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved contributions.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected contributions.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include published contributions.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to search contributions.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('abstract', 'like', "%{$search}%")
              ->orWhere('keywords', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'published' => 'info'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get the type badge color.
     */
    public function getTypeBadgeAttribute()
    {
        $badges = [
            'research_paper' => 'primary',
            'case_study' => 'success',
            'methodology' => 'info',
            'review' => 'warning'
        ];

        return $badges[$this->type] ?? 'secondary';
    }

    /**
     * Check if the contribution is pending review.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the contribution is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the contribution is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the contribution is published.
     */
    public function isPublished()
    {
        return $this->status === 'published';
    }

    /**
     * Get the formatted file size.
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Get the submission date in a readable format.
     */
    public function getSubmissionDateAttribute()
    {
        return $this->created_at->format('M j, Y');
    }

    /**
     * Get the review date in a readable format.
     */
    public function getReviewDateAttribute()
    {
        return $this->reviewed_at ? $this->reviewed_at->format('M j, Y') : 'Not reviewed';
    }
} 