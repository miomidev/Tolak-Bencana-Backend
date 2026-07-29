<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'place_name',
    'latitude',
    'longitude',
    'address',
    'is_primary',
    'icon_type'
])]
class SavedLocation extends Model
{
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Get the user that saved this location.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
