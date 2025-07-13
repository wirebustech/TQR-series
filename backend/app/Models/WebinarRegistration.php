<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class WebinarRegistration extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'webinar_id',
        'user_id',
        'name',
        'email',
        'phone',
        'organization',
        'attended',
        'attended_at',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attended' => 'boolean',
        'attended_at' => 'datetime',
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
     * Get the webinar that this registration belongs to.
     */
    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }

    /**
     * Get the user that made this registration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the registration is confirmed.
     */
    public function getIsConfirmedAttribute()
    {
        return $this->created_at !== null;
    }

    /**
     * Check if the registration is for an upcoming webinar.
     */
    public function getIsUpcomingAttribute()
    {
        return $this->webinar && $this->webinar->is_upcoming;
    }

    /**
     * Check if the registration is for a live webinar.
     */
    public function getIsLiveAttribute()
    {
        return $this->webinar && $this->webinar->is_live;
    }

    /**
     * Check if the registration is for a completed webinar.
     */
    public function getIsCompletedAttribute()
    {
        return $this->webinar && $this->webinar->is_completed;
    }

    /**
     * Get the registration status.
     */
    public function getStatusAttribute()
    {
        if ($this->attended) {
            return 'attended';
        }

        if ($this->is_live) {
            return 'live';
        }

        if ($this->is_upcoming) {
            return 'upcoming';
        }

        if ($this->is_completed) {
            return 'missed';
        }

        return 'registered';
    }

    /**
     * Get the formatted registration date.
     */
    public function getFormattedRegistrationDateAttribute()
    {
        return $this->created_at->format('F j, Y \a\t g:i A');
    }

    /**
     * Get the formatted attendance date.
     */
    public function getFormattedAttendanceDateAttribute()
    {
        if (!$this->attended_at) {
            return 'Not attended';
        }

        return $this->attended_at->format('F j, Y \a\t g:i A');
    }

    /**
     * Scope a query to only include confirmed registrations.
     */
    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('created_at');
    }

    /**
     * Scope a query to only include attended registrations.
     */
    public function scopeAttended($query)
    {
        return $query->where('attended', true);
    }

    /**
     * Scope a query to only include non-attended registrations.
     */
    public function scopeNotAttended($query)
    {
        return $query->where('attended', false);
    }

    /**
     * Scope a query to only include registrations for upcoming webinars.
     */
    public function scopeUpcoming($query)
    {
        return $query->whereHas('webinar', function ($q) {
            $q->where('scheduled_at', '>', Carbon::now());
        });
    }

    /**
     * Scope a query to only include registrations for live webinars.
     */
    public function scopeLive($query)
    {
        $now = Carbon::now();
        return $query->whereHas('webinar', function ($q) use ($now) {
            $q->where('scheduled_at', '<=', $now)
              ->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration MINUTE) > ?', [$now]);
        });
    }

    /**
     * Scope a query to only include registrations for completed webinars.
     */
    public function scopeCompleted($query)
    {
        $now = Carbon::now();
        return $query->whereHas('webinar', function ($q) use ($now) {
            $q->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration MINUTE) <= ?', [$now]);
        });
    }

    /**
     * Scope a query to only include registrations by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include registrations for a specific webinar.
     */
    public function scopeForWebinar($query, $webinarId)
    {
        return $query->where('webinar_id', $webinarId);
    }

    /**
     * Mark the registration as attended.
     */
    public function markAsAttended()
    {
        $this->update([
            'attended' => true,
            'attended_at' => Carbon::now()
        ]);
    }

    /**
     * Mark the registration as not attended.
     */
    public function markAsNotAttended()
    {
        $this->update([
            'attended' => false,
            'attended_at' => null
        ]);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set user_id when creating if not provided
        static::creating(function ($registration) {
            if (!$registration->user_id && auth()->check()) {
                $registration->user_id = auth()->id();
            }
        });
    }
} 