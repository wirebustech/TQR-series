<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'language',
        'description',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this page.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this page.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include draft pages.
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope a query to filter by language.
     */
    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope a query to search pages.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Check if the page is published.
     */
    public function isPublished()
    {
        return $this->is_published;
    }

    /**
     * Check if the page is a draft.
     */
    public function isDraft()
    {
        return !$this->is_published;
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_published 
            ? '<span class="badge bg-success">Published</span>'
            : '<span class="badge bg-secondary">Draft</span>';
    }

    /**
     * Get the status text.
     */
    public function getStatusTextAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    /**
     * Get the formatted creation date.
     */
    public function getFormattedCreatedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('M j, Y g:i A') : 'N/A';
    }

    /**
     * Get the language name.
     */
    public function getLanguageNameAttribute()
    {
        $languages = [
            'en' => 'English',
            'fr' => 'Français',
            'es' => 'Español'
        ];
        
        return $languages[$this->language] ?? ucfirst($this->language);
    }

    /**
     * Get the language flag.
     */
    public function getLanguageFlagAttribute()
    {
        $flags = [
            'en' => '🇺🇸',
            'fr' => '🇫🇷',
            'es' => '🇪🇸'
        ];
        
        return $flags[$this->language] ?? '🌐';
    }
} 