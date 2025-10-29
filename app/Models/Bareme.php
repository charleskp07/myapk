<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bareme extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
    ];

    public function evaluation(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function noteAppreciations(): HasMany
    {
        return $this->hasMany(NoteAppreciation::class);
    }
}
