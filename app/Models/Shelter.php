<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'latitude',
    'longitude',
    'address',
    'capacity',
    'current_occupancy',
    'status',
    'contact'
])]
class Shelter extends Model
{
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'capacity' => 'integer',
            'current_occupancy' => 'integer',
        ];
    }
}
