<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignation_id',
        'teacher_id',
        'classroom_id',
        'subject_id',
        'day_of_week',
        'start_time',
        'end_time',
        'duration_minutes',
        'room',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relations
    public function assignation():BelongsTo
    {
        return $this->belongsTo(Assignation::class);
    }

    public function teacher():BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom():BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject():BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Vérifier si ce créneau chevauche un autre
     */
    public function overlaps(string $day, string $startTime, string $endTime): bool
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $thisStart = Carbon::parse($this->start_time);
        $thisEnd = Carbon::parse($this->end_time);

        return $this->day_of_week === $day &&
            $start->lt($thisEnd) &&
            $end->gt($thisStart);
    }

    /**
     * Formater pour FullCalendar
     */
    public function toFullCalendarEvent(): array
    {
        // Calculer une date fictive pour cette semaine
        $daysMap = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 0,
        ];

        $dayNum = $daysMap[$this->day_of_week] ?? 1;
        $date = now()->startOfWeek()->addDays($dayNum - 1)->format('Y-m-d');

        // Obtenir le nom complet de l'enseignant
        $teacherName = $this->teacher->full_name ?? 
                      ($this->teacher->first_name . ' ' . $this->teacher->last_name) ?? 
                      'Enseignant';

        // Obtenir le nom de la classe
        $classroomName = $this->classroom->full_name ?? 
                        ($this->classroom->name . ' ' . $this->classroom->section) ?? 
                        'Classe';

        // Générer une couleur basée sur le nom de la matière (pour cohérence visuelle)
        $subjectName = $this->subject->name ?? 'Matière';
        $color = $this->generateColorFromString($subjectName);

        return [
            'id' => $this->id,
            'title' => $subjectName,
            'start' => $date . 'T' . $this->start_time,
            'end' => $date . 'T' . $this->end_time,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'textColor' => '#ffffff',
            'extendedProps' => [
                'teacher' => $teacherName,
                'classroom' => $classroomName,
                'room' => $this->room ?? 'N/A',
                'subject' => $subjectName,
                'duration' => $this->duration_minutes . ' min',
            ],
        ];
    }

    /**
     * Générer une couleur à partir d'une chaîne (pour cohérence visuelle)
     */
    private function generateColorFromString(string $string): string
    {
        $colors = [
            '#3788d8', // Bleu
            '#28a745', // Vert
            '#ffc107', // Jaune
            '#dc3545', // Rouge
            '#6f42c1', // Violet
            '#fd7e14', // Orange
            '#20c997', // Turquoise
            '#e83e8c', // Rose
            '#17a2b8', // Cyan
            '#6c757d', // Gris
        ];

        $hash = crc32($string);
        return $colors[abs($hash) % count($colors)];
    }

    /**
     * Scope: Emploi du temps d'une classe
     */
    public function scopeForClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId)
            ->where('is_active', true);
    }

    /**
     * Scope: Emploi du temps d'un enseignant
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId)
            ->where('is_active', true);
    }
}
