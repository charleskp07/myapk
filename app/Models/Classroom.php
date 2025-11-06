<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'level',
        'name',
        'section',
    ];


    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function assignations(): HasMany
    {
        return $this->hasMany(Assignation::class);
    }


    public function evaluations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Evaluation::class,      // le modèle final
            Assignation::class,     // le modèle intermédiaire
            'classroom_id',         // clé étrangère sur la table assignations
            'assignation_id',       // clé étrangère sur la table evaluations
            'id',                   // clé locale sur classrooms
            'id'                    // clé locale sur assignations
        );
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->section;
    }
}
