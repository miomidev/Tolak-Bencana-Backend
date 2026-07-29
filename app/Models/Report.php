<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'latitude',
    'longitude',
    'address',
    'description',
    'photo_url',
    'severity',
    'status',
    'reported_at'
])]
class Report extends Model
{
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'reported_at' => 'datetime',
        ];
    }

    /**
     * Get the user that created the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
