<?php

namespace App\Models;

use App\Traits\HasTeam;
use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory, HasTeam;

    protected $fillable = [
        'name',
        'files',
        'notes',
    ];

    protected $casts = [
        'files' => 'array',
    ];
}
