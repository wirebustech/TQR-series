<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Webinar extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'scheduled_at',
        'duration',
        'max_attendees',
        'platform',
        'meeting_url',
        'tags',
        'status',
        'requires_registration',
        'is_public',
        'image',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration' => 'integer',
        'max_attendees' => 'integer',
        'requires_registration' => 'boolean',
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Get the user that created the webinar.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the registrations for this webinar.
     */
    public function registrations()
    {
        return $this->hasMany(WebinarRegistration::class);
    }

    /**
     * Get the attendees count for this webinar.
     */
    public function getAttendeesCountAttribute()
    {
        return $this->registrations()->count();
    }

    /**
     * Check if the webinar is upcoming.
     */
    public function getIsUpcomingAttribute()
    {
        return $this->scheduled_at && $this->scheduled_at->isFuture();
    }

    /**
     * Check if the webinar is live (within duration window).
     */
    public function getIsLiveAttribute()
    {
        if (!$this->scheduled_at || !$this->duration) {
            return false;
        }

        $startTime = $this->scheduled_at;
        $endTime = $startTime->copy()->addMinutes($this->duration);
        $now = Carbon::now();

        return $now->between($startTime, $endTime);
    }

    /**
     * Check if the webinar is completed.
     */
    public function getIsCompletedAttribute()
    {
        if (!$this->scheduled_at || !$this->duration) {
            return false;
        }

        $endTime = $this->scheduled_at->copy()->addMinutes($this->duration);
        return Carbon::now()->isAfter($endTime);
    }

    /**
     * Check if the webinar is full.
     */
    public function getIsFullAttribute()
    {
        if (!$this->max_attendees) {
            return false;
        }

        return $this->attendees_count >= $this->max_attendees;
    }

    /**
     * Get the remaining spots for this webinar.
     */
    public function getRemainingSpotsAttribute()
    {
        if (!$this->max_attendees) {
            return null;
        }

        return max(0, $this->max_attendees - $this->attendees_count);
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return 'TBD';
        }

        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . ' minutes';
    }

    /**
     * Get the formatted scheduled date.
     */
    public function getFormattedDateAttribute()
    {
        if (!$this->scheduled_at) {
            return 'TBD';
        }

        return $this->scheduled_at->format('F j, Y');
    }

    /**
     * Get the formatted scheduled time.
     */
    public function getFormattedTimeAttribute()
    {
        if (!$this->scheduled_at) {
            return 'TBD';
        }

        return $this->scheduled_at->format('g:i A');
    }

    /**
     * Get the formatted scheduled date and time.
     */
    public function getFormattedDateTimeAttribute()
    {
        if (!$this->scheduled_at) {
            return 'TBD';
        }

        return $this->scheduled_at->format('F j, Y \a\t g:i A');
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Get the tags as an array.
     */
    public function getTagsArrayAttribute()
    {
        if (!$this->tags) {
            return [];
        }

        return array_map('trim', explode(',', $this->tags));
    }

    /**
     * Scope a query to only include published webinars.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include public webinars.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope a query to only include upcoming webinars.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', Carbon::now());
    }

    /**
     * Scope a query to only include live webinars.
     */
    public function scopeLive($query)
    {
        $now = Carbon::now();
        return $query->where('scheduled_at', '<=', $now)
                    ->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration MINUTE) > ?', [$now]);
    }

    /**
     * Scope a query to only include completed webinars.
     */
    public function scopeCompleted($query)
    {
        $now = Carbon::now();
        return $query->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration MINUTE) <= ?', [$now]);
    }

    /**
     * Scope a query to only include webinars by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to search webinars.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('tags', 'like', "%{$search}%");
        });
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set user_id when creating
        static::creating(function ($webinar) {
            if (!$webinar->user_id && auth()->check()) {
                $webinar->user_id = auth()->id();
            }
        });
    }
} 