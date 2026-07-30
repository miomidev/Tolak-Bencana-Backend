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
    'comment'
])]
class ReportComment extends Model
{
    use HasFactory, HasUuids;

    /**
     * Get the user who commented.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the report that was commented on.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
