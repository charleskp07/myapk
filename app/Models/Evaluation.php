<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignation_id',
        'bareme_id',
        'title',
        'date',
        'type',
        'breakdown_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function assignation(): BelongsTo
    {
        return $this->belongsTo(Assignation::class);
    }

    public function bareme(): BelongsTo
    {
        return $this->belongsTo(Bareme::class);
    }
    
    public function breakdown(): BelongsTo
    {
        return $this->belongsTo(Breakdown::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
