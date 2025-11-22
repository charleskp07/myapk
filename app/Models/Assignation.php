<?php

namespace App\Models;

use App\Enums\ClassroomLevelEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignation extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'subject_id',
        'teacher_id',
        'coefficient',
        'weekly_hours',
    ];

    protected $casts = [
        'coefficient' => 'float',
        'weekly_hours' => 'integer',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Calculer les blocs horaires nécessaires selon le niveau
     */
    public function calculateTimeBlocks(): array
    {
        $level = $this->classroom->level;
        $maxBlockSize = $level === ClassroomLevelEnums::LYCEE->value ? 2 : 1; // Lycée: 2h, Collège: 1h
        $totalHours = $this->weekly_hours;

        $blocks = [];
        $remaining = $totalHours;

        while ($remaining > 0) {
            $blockSize = min($maxBlockSize, $remaining);
            $blocks[] = $blockSize;
            $remaining -= $blockSize;
        }

        return $blocks;
    }

    /**
     * Vérifier si l'assignation a déjà un emploi du temps généré
     */
    public function hasSchedule(): bool
    {
        return $this->schedules()->exists();
    }
}
