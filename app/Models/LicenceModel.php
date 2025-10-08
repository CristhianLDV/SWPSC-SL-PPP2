<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenceModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'files',
        'notes',
    ];

    protected $casts = [
        'files' => 'array',
    ];
}
