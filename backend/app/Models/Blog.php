<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'slug',
        'status',
        'featured_image',
        'meta_title',
        'meta_description',
        'published_at',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who created this blog post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include published blog posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include draft blog posts.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to search blog posts.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    /**
     * Check if the blog post is published.
     */
    public function isPublished()
    {
        return $this->status === 'published';
    }

    /**
     * Check if the blog post is a draft.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'secondary',
            'published' => 'success',
            'archived' => 'warning'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get the formatted published date.
     */
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('M j, Y') : 'Not published';
    }
} 