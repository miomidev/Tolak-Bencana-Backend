<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'app_name',
    'description',
    'developer',
    'website',
    'support_email',
    'privacy_policy_url',
    'terms_url'
])]
class AppInfo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'app_info';
}
