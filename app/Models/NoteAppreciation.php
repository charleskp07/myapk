<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NoteAppreciation extends Model
{
    use HasFactory;

    protected $fillable = [
        'bareme_id',
        'appreciation',
        'min_value',
        'max_value',
    ];

    
    public function bareme(): BelongsTo
    {
        return $this->belongsTo(Bareme::class);
    }
    

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
