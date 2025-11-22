<?php

namespace App\Models;

use App\Enums\TimePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'time_preference',
    ];

    public function assignations(): HasMany
    {
        return $this->hasMany(Assignation::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Vérifier si la matière peut être placée à ce créneau
     */
    public function canBePlacedAt(string $startTime, string $endTime): bool
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);

        switch ($this->time_preference) {
            case TimePreference::MATIN->value:
                // Avant 12h
                return $start->hour < 12;

            case TimePreference::APRES_MIDI->value:
                // Entre 15h et 17h
                return $start->hour >= 15 && $end->hour <= 17;

            case TimePreference::SOIR->value:
                // Après 17h (soirée)
                return $start->hour >= 17;

            case TimePreference::AVANT_PAUSE->value:
                // Avant 09h45 uniquement
                return $start->hour < 9 || ($start->hour === 9 && $start->minute < 45);

            case TimePreference::FLEXIBLE->value:
            default:
                return true;
        }
    }
}
