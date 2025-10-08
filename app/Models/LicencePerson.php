<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @method static create(string[] $array)
 * @method static first()
 * @method static whereNotNull(string $string)
 * @method static whereLicenceId(mixed $id)
 */
class LicencePerson extends Pivot
{
    use HasFactory ;

    protected $fillable = [
        'files',
        'notes',
    
    ];

    protected $casts = [
        'files' => 'array',
    ];

    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
