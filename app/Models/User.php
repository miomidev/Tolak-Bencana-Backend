<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'avatar_url', 'username', 'email', 'password', 'phone', 'role', 'fcm_token'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

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
        ];
    }

    /**
     * Get the reports created by the user.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get the notifications received by the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the notification settings for the user.
     */
    public function notificationSetting(): HasOne
    {
        return $this->hasOne(NotificationSetting::class);
    }

    /**
     * Get the saved locations for the user.
     */
    public function savedLocations(): HasMany
    {
        return $this->hasMany(SavedLocation::class);
    }

    /**
     * Get the likes given by this user.
     */
    public function reportLikes(): HasMany
    {
        return $this->hasMany(ReportLike::class);
    }

    /**
     * Get the comments made by this user.
     */
    public function reportComments(): HasMany
    {
        return $this->hasMany(ReportComment::class);
    }
}

