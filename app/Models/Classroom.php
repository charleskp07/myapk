<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'name',
        'section',
    ];


    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function assignations(): HasMany
    {
        return $this->hasMany(Assignation::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->section;
    }
}
