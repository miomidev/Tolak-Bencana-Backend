<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'report_id',
])]
class ReportLike extends Model
{
    use HasFactory, HasUuids;

    /**
     * Get the user who liked the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the report that was liked.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
