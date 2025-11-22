<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * Helper pour gérer les créneaux horaires
 * Gère toutes les contraintes : plages horaires, pause, soirées interdites
 */
class TimeSlot
{
    public string $day;
    public Carbon $start;
    public Carbon $end;
    public int $durationHours;

    public function __construct(string $day, string $startTime, int $durationHours)
    {
        $this->day = $day;
        $this->start = Carbon::parse($startTime);
        $this->durationHours = $durationHours;
        $this->end = $this->start->copy()->addHours($durationHours);
    }

    /**
     * Obtenir tous les créneaux horaires possibles dans la semaine
     * Selon les contraintes :
     * - Matin : 07h00 → 09h45
     * - Pause : 09h45 → 10h00 (interdite)
     * - Fin matinée : 10h00 → 12h00
     * - Après-midi : 15h00 → 17h00
     * - Soirée : 17h00+ (optionnelle, sauf mercredi/vendredi)
     */
    public static function getAllPossibleSlots(int $maxBlockSize = 2): array
    {
        $slots = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        foreach ($days as $day) {
            // MATIN : 07h00 → 09h45 (avant pause)
            // Créneaux de 1h
            $slots[] = new self($day, '07:00', 1);
            $slots[] = new self($day, '08:00', 1);
            $slots[] = new self($day, '08:45', 1); // Jusqu'à 09h45
            
            // Créneaux de 2h (si maxBlockSize >= 2, pour lycée)
            if ($maxBlockSize >= 2) {
                $slots[] = new self($day, '07:00', 2); // 07h-09h
                $slots[] = new self($day, '07:45', 2); // 07h45-09h45 (juste avant pause)
            }

            // FIN MATINÉE : 10h00 → 12h00 (après pause)
            $slots[] = new self($day, '10:00', 1);
            $slots[] = new self($day, '11:00', 1);
            
            if ($maxBlockSize >= 2) {
                $slots[] = new self($day, '10:00', 2); // 10h-12h
            }

            // APRÈS-MIDI : 15h00 → 17h00
            // Interdit le mercredi et vendredi après-midi
            if (!in_array($day, ['wednesday', 'friday'])) {
                $slots[] = new self($day, '15:00', 1);
                $slots[] = new self($day, '16:00', 1);
                
                if ($maxBlockSize >= 2) {
                    $slots[] = new self($day, '15:00', 2); // 15h-17h
                }
            }

            // SOIRÉE : 17h00+ (optionnelle)
            // Interdit le mercredi et vendredi soir
            if (!in_array($day, ['wednesday', 'friday'])) {
                $slots[] = new self($day, '17:00', 1);
                $slots[] = new self($day, '18:00', 1);
                
                if ($maxBlockSize >= 2) {
                    $slots[] = new self($day, '17:00', 2); // 17h-19h
                }
            }
        }

        // Filtrer les créneaux invalides (qui chevauchent la pause)
        return array_filter($slots, function ($slot) {
            return $slot->isValid();
        });
    }

    /**
     * Vérifier si le créneau est dans la pause interdite (09h45-10h00)
     */
    public function isInBreakTime(): bool
    {
        $breakStart = Carbon::parse('09:45');
        $breakEnd = Carbon::parse('10:00');

        // Le créneau chevauche la pause
        return $this->start->lt($breakEnd) && $this->end->gt($breakStart);
    }

    /**
     * Vérifier si le créneau est en soirée interdite (mercredi/vendredi après 15h)
     */
    public function isInForbiddenEvening(): bool
    {
        if (!in_array($this->day, ['wednesday', 'friday'])) {
            return false;
        }

        // Mercredi et vendredi : pas de cours après 15h
        return $this->start->hour >= 15;
    }

    /**
     * Vérifier si le créneau est valide selon toutes les contraintes
     */
    public function isValid(): bool
    {
        return !$this->isInBreakTime() && !$this->isInForbiddenEvening();
    }

    /**
     * Obtenir l'heure de fin formatée
     */
    public function getEndTime(): string
    {
        return $this->end->format('H:i');
    }

    /**
     * Obtenir l'heure de début formatée
     */
    public function getStartTime(): string
    {
        return $this->start->format('H:i');
    }

    /**
     * Vérifier si deux créneaux se chevauchent
     */
    public function overlaps(TimeSlot $other): bool
    {
        if ($this->day !== $other->day) {
            return false;
        }

        return $this->start->lt($other->end) && $this->end->gt($other->start);
    }

    /**
     * Vérifier si le créneau est dans une plage horaire donnée
     */
    public function isInTimeRange(string $startTime, string $endTime): bool
    {
        $rangeStart = Carbon::parse($startTime);
        $rangeEnd = Carbon::parse($endTime);

        return $this->start->gte($rangeStart) && $this->end->lte($rangeEnd);
    }

    /**
     * Obtenir la période du jour (morning, afternoon, evening)
     */
    public function getTimePeriod(): string
    {
        $hour = $this->start->hour;

        if ($hour < 12) {
            return 'morning';
        } elseif ($hour < 17) {
            return 'afternoon';
        } else {
            return 'evening';
        }
    }
}
