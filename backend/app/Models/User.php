<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'organization',
        'phone',
        'bio',
        'is_admin',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the user's contributions.
     */
    public function contributions()
    {
        return $this->hasMany(ResearchContribution::class);
    }

    /**
     * Get the user's webinar registrations.
     */
    public function webinarRegistrations()
    {
        return $this->hasMany(WebinarRegistration::class);
    }

    /**
     * Get the user's donations.
     */
    public function donations()
    {
        return $this->hasMany(SupportDonation::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function getIsAdminAttribute($value)
    {
        return $value || $this->role === 'admin' || $this->role === 'moderator';
    }

    /**
     * Check if the user is an admin (method).
     */
    public function isAdmin()
    {
        return $this->is_admin || $this->role === 'admin' || $this->role === 'moderator';
    }

    /**
     * Check if the user is active.
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user is suspended.
     */
    public function getIsSuspendedAttribute()
    {
        return $this->status === 'suspended';
    }

    /**
     * Get the user's display name.
     */
    public function getDisplayNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get the user's role badge.
     */
    public function getRoleBadgeAttribute()
    {
        $badges = [
            'user' => 'secondary',
            'admin' => 'danger',
            'moderator' => 'warning',
            'researcher' => 'info'
        ];

        return $badges[$this->role] ?? 'secondary';
    }

    /**
     * Get the user's status badge.
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'secondary' => 'inactive',
            'suspended' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope a query to only include verified users.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope a query to search users.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('organization', 'like', "%{$search}%");
        });
    }
}
