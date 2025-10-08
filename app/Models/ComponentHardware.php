<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @method static create(string[] $array)
 * @method static first()
 * @method static whereHardwareId(mixed $id)
 * @method static whereNotNull(string $string)
 * @method static whereComponentId(mixed $id)
 */
class ComponentHardware extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'files',
        'notes',
        'checked_out_at',
        
    ];

    protected $casts = [
        'files' => 'array',
    ];

    public function hardware(): BelongsTo
    {
        return $this->belongsTo(Hardware::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}
