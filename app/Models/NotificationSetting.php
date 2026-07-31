<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'flood_alert',
    'report_update',
    'emergency_alert',
    'location_alert',
])]
class NotificationSetting extends Model
{
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'flood_alert' => 'boolean',
            'report_update' => 'boolean',
            'emergency_alert' => 'boolean',
            'location_alert' => 'boolean',
        ];
    }

    /**
     * Get the user that owns these notification settings.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
