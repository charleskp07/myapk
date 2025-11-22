<?php

namespace App\Models;

use App\Enums\ClassroomLevelEnums;
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


    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->section;
    }


    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Obtenir la durée maximale d'un cours selon le niveau
     */
    public function getMaxCourseDuration(): int
    {
        return $this->level === ClassroomLevelEnums::LYCEE->value ? 2 : 1; // En heures
    }

    /**
     * Vérifier si la classe a déjà un cours à ce moment
     */
    public function hasConflictAt(string $day, string $startTime, string $endTime): bool
    {
        return $this->schedules()
            ->where('day_of_week', $day)
            ->where('is_active', true)
            ->get()
            ->contains(function ($schedule) use ($day, $startTime, $endTime) {
                return $schedule->overlaps($day, $startTime, $endTime);
            });
    }
}
