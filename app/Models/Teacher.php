<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo',
        'first_name',
        'last_name',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'email',
        'phone',
        'nationality',
        'speciality',
        'availability',
    ];

    protected $casts = [
        'availability' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classroom(): HasOne
    {
        return $this->hasOne(Classroom::class);
    }

    public function assignations(): HasMany
    {
        return $this->hasMany(Assignation::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return Carbon::parse($this->date_of_birth)->age;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : asset('images/default-avatar.png');
    }


    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Vérifier si l'enseignant est disponible à un créneau donné
     */
    public function isAvailableAt(string $day, string $startTime, string $endTime): bool
    {
        if (!$this->availability || !isset($this->availability[$day])) {
            return false;
        }

        $slots = $this->availability[$day];
        if (empty($slots)) {
            return false;
        }

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        foreach ($slots as $slot) {
            [$slotStart, $slotEnd] = explode('-', $slot);
            $availStart = Carbon::parse($slotStart);
            $availEnd = Carbon::parse($slotEnd);

            // Le créneau doit être entièrement dans la disponibilité
            if ($start->gte($availStart) && $end->lte($availEnd)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifier si l'enseignant a déjà un cours à ce moment
     */
    public function hasConflictAt(string $day, string $startTime, string $endTime): bool
    {
        return $this->schedules()
            ->where('day_of_week', $day)
            ->where('is_active', true)
            ->get()
            ->contains(function ($schedule) use ($startTime, $endTime) {
                return $schedule->overlaps($schedule->day_of_week, $startTime, $endTime);
            });
    }
}
