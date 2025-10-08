<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(string[] $array)
 */
class HardwareModel extends Model
{
    use HasFactory ;

    protected $fillable = [
        'name',
        'category_id',
        'files',
        'notes',
    ];

    protected $casts = [
        'files' => 'array',
    ];
}
