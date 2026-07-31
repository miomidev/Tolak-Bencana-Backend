<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertLevel extends Model
{
    protected $fillable = [
        'name',
        'color',
        'min_height',
        'max_height',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_height' => 'integer',
        'max_height' => 'integer',
    ];
}
