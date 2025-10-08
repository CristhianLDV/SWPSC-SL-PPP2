<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(string[] $array)
 */
class HardwareStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'files',
        'notes',
    ];

    protected $casts = [
        'files' => 'array',
    ];

    public function hardware(): HasMany
    {
        return $this->hasMany(Hardware::class);
    }
}
