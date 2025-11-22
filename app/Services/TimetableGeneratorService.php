<?php

namespace App\Services;

use App\Enums\ClassroomLevelEnums;
use App\Enums\TimePreference;
use App\Helpers\TimeSlot;
use App\Models\Assignation;
use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service de génération automatique d'emploi du temps
 * 
 * Algorithme : Backtracking amélioré avec heuristiques
 * - Trie les assignations par priorité (contraintes strictes d'abord)
 * - Place les cours en respectant toutes les contraintes
 * - Gère les conflits enseignant/classe
 * - Optimise la répartition des cours
 */
class TimetableGeneratorService
{
    private Classroom $classroom;
    private array $assignations = [];
    private array $placedSchedules = [];
    private array $availableSlots = [];
    private int $maxAttempts = 10000; // Augmenté pour plus de flexibilité
    private int $currentAttempts = 0;

    /**
     * Générer l'emploi du temps pour une classe
     */
    public function generate(int $classroomId): array
    {
        try {
            DB::beginTransaction();

            // Charger la classe et ses assignations
            $this->classroom = Classroom::with([
                'assignations.teacher',
                'assignations.subject'
            ])->findOrFail($classroomId);

            $this->assignations = $this->classroom->assignations->toArray();

            if (empty($this->assignations)) {
                throw new \Exception("Aucune assignation trouvée pour cette classe");
            }

            // Supprimer l'ancien emploi du temps
            Schedule::where('classroom_id', $classroomId)->delete();

            // Déterminer la taille max des blocs selon le niveau
            $maxBlockSize = $this->classroom->level === ClassroomLevelEnums::LYCEE->value ? 2 : 1;

            // Initialiser les créneaux disponibles
            $this->availableSlots = TimeSlot::getAllPossibleSlots($maxBlockSize);

            // Trier les assignations par priorité
            $this->sortAssignationsByPriority();

            // Générer l'emploi du temps avec backtracking
            $success = $this->generateWithBacktracking(0);

            if (!$success) {
                DB::rollBack();
                
                // Analyser les problèmes pour donner un message plus détaillé
                $issues = $this->analyzeIssues();
                
                $message = "Impossible de générer un emploi du temps valide avec toutes les contraintes.";
                
                if (!empty($issues)) {
                    $message .= "\n\nProblèmes détectés :\n";
                    foreach ($issues as $issue) {
                        $message .= "• " . $issue . "\n";
                    }
                }
                
                $message .= "\nSuggestions :\n";
                $message .= "• Vérifiez les disponibilités des enseignants (format JSON dans la table teachers)\n";
                $message .= "• Assouplissez les contraintes horaires des matières (time_preference)\n";
                $message .= "• Vérifiez que le nombre d'heures totales est réaliste par rapport aux créneaux disponibles\n";
                $message .= "• Réduisez le nombre d'heures par matière si nécessaire\n";
                
                throw new \Exception($message);
            }

            // Sauvegarder les créneaux placés
            $this->savePlacedSchedules();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Emploi du temps généré avec succès',
                'schedules_count' => count($this->placedSchedules),
                'schedules' => $this->placedSchedules,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur génération emploi du temps: ' . $e->getMessage(), [
                'classroom_id' => $classroomId,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'schedules' => [],
            ];
        }
    }

    /**
     * Algorithme de backtracking amélioré pour placer les cours
     */
    private function generateWithBacktracking(int $assignationIndex): bool
    {
        $this->currentAttempts++;
        
        // Limite de sécurité pour éviter les boucles infinies
        if ($this->currentAttempts > $this->maxAttempts) {
            return false;
        }

        // Cas de base : toutes les assignations sont placées
        if ($assignationIndex >= count($this->assignations)) {
            return true;
        }

        $assignationData = $this->assignations[$assignationIndex];
        $assignation = Assignation::with(['teacher', 'subject', 'classroom'])
            ->find($assignationData['id']);

        if (!$assignation) {
            return false;
        }

        // Calculer les blocs nécessaires pour cette assignation
        $blocks = $assignation->calculateTimeBlocks();
        $blocksToPlace = count($blocks);
        $placedBlocks = $this->countPlacedBlocksForAssignation($assignation->id);

        // Si tous les blocs sont déjà placés, passer à la suivante
        if ($placedBlocks >= $blocksToPlace) {
            return $this->generateWithBacktracking($assignationIndex + 1);
        }

        // Essayer de placer chaque bloc restant
        foreach ($blocks as $blockIndex => $blockDuration) {
            // Vérifier si ce bloc est déjà placé
            if ($this->isBlockAlreadyPlaced($assignation->id, $blockIndex)) {
                continue;
            }

            // Trier les créneaux par priorité (meilleurs créneaux en premier)
            $sortedSlots = $this->getPrioritizedSlots($assignation, $blockDuration);

            foreach ($sortedSlots as $slot) {
                if ($this->canPlaceAt($assignation, $slot)) {
                    // Placer le cours
                    $this->placeSchedule($assignation, $slot, $blockIndex);

                    // Récursion sur l'assignation suivante ou le bloc suivant
                    $nextSuccess = $this->generateWithBacktracking($assignationIndex);

                    if ($nextSuccess) {
                        return true;
                    }

                    // Backtrack : retirer le placement
                    $this->removePlacedSchedule($assignation->id, $slot, $blockIndex);
                }
            }
        }

        return false;
    }

    /**
     * Obtenir les créneaux prioritaires pour une assignation
     * (heuristique : meilleurs créneaux en premier)
     */
    private function getPrioritizedSlots(Assignation $assignation, int $blockDuration): array
    {
        $matchingSlots = array_values(array_filter($this->availableSlots, function ($slot) use ($blockDuration) {
            return $slot->durationHours === $blockDuration && $slot->isValid();
        }));

        // Trier par priorité
        usort($matchingSlots, function ($a, $b) use ($assignation) {
            // 1. Respecter la préférence horaire de la matière
            $aMatches = $this->matchesTimePreference($assignation->subject, $a);
            $bMatches = $this->matchesTimePreference($assignation->subject, $b);
            if ($aMatches !== $bMatches) {
                return $bMatches ? -1 : 1;
            }

            // 2. Préférer les créneaux où l'enseignant est disponible
            $aTeacherAvailable = $assignation->teacher->isAvailableAt(
                $a->day,
                $a->getStartTime(),
                $a->getEndTime()
            );
            $bTeacherAvailable = $assignation->teacher->isAvailableAt(
                $b->day,
                $b->getStartTime(),
                $b->getEndTime()
            );
            if ($aTeacherAvailable !== $bTeacherAvailable) {
                return $bTeacherAvailable ? -1 : 1;
            }

            // 3. Éviter les trous (préférer les créneaux consécutifs)
            $aHasGap = $this->hasGapAroundSlot($a);
            $bHasGap = $this->hasGapAroundSlot($b);
            if ($aHasGap !== $bHasGap) {
                return $aHasGap ? 1 : -1;
            }

            return 0;
        });

        return $matchingSlots;
    }

    /**
     * Vérifier si un créneau correspond à la préférence horaire de la matière
     */
    private function matchesTimePreference($subject, TimeSlot $slot): bool
    {
        if (!$subject->time_preference || $subject->time_preference === TimePreference::FLEXIBLE->value) {
            return true;
        }

        $startTime = $slot->getStartTime();
        $endTime = $slot->getEndTime();
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);

        switch ($subject->time_preference) {
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

            default:
                return true;
        }
    }

    /**
     * Vérifier si un cours peut être placé à un créneau donné
     */
    private function canPlaceAt(Assignation $assignation, TimeSlot $slot): bool
    {
        // 1. Vérifier que le créneau est valide (pas dans pause ou soirée interdite)
        if (!$slot->isValid()) {
            return false;
        }

        // 2. Vérifier la préférence horaire de la matière
        if (!$this->matchesTimePreference($assignation->subject, $slot)) {
            return false;
        }

        // 3. Vérifier la disponibilité de l'enseignant
        if (!$assignation->teacher->isAvailableAt(
            $slot->day,
            $slot->getStartTime(),
            $slot->getEndTime()
        )) {
            return false;
        }

        // 4. Vérifier les conflits enseignant (pas de double cours en même temps)
        if ($this->hasTeacherConflict($assignation->teacher_id, $slot)) {
            return false;
        }

        // 5. Vérifier les conflits classe (pas de double cours en même temps)
        if ($this->hasClassroomConflict($slot)) {
            return false;
        }

        return true;
    }

    /**
     * Placer un cours dans un créneau
     */
    private function placeSchedule(Assignation $assignation, TimeSlot $slot, int $blockIndex): void
    {
        $this->placedSchedules[] = [
            'assignation_id' => $assignation->id,
            'teacher_id' => $assignation->teacher_id,
            'classroom_id' => $assignation->classroom_id,
            'subject_id' => $assignation->subject_id,
            'day_of_week' => $slot->day,
            'start_time' => $slot->getStartTime(),
            'end_time' => $slot->getEndTime(),
            'duration_minutes' => $slot->durationHours * 60,
            'block_index' => $blockIndex, // Pour le backtracking
        ];
    }

    /**
     * Retirer un placement (backtracking)
     */
    private function removePlacedSchedule(int $assignationId, TimeSlot $slot, int $blockIndex): void
    {
        $this->placedSchedules = array_filter($this->placedSchedules, function ($schedule) use ($assignationId, $slot, $blockIndex) {
            return !(
                $schedule['assignation_id'] === $assignationId &&
                $schedule['day_of_week'] === $slot->day &&
                $schedule['start_time'] === $slot->getStartTime() &&
                ($schedule['block_index'] ?? -1) === $blockIndex
            );
        });
    }

    /**
     * Vérifier conflit enseignant
     */
    private function hasTeacherConflict(int $teacherId, TimeSlot $slot): bool
    {
        foreach ($this->placedSchedules as $placed) {
            if ($placed['teacher_id'] === $teacherId && $placed['day_of_week'] === $slot->day) {
                $placedSlot = new TimeSlot(
                    $placed['day_of_week'],
                    $placed['start_time'],
                    $placed['duration_minutes'] / 60
                );
                if ($slot->overlaps($placedSlot)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Vérifier conflit classe
     */
    private function hasClassroomConflict(TimeSlot $slot): bool
    {
        foreach ($this->placedSchedules as $placed) {
            if ($placed['day_of_week'] === $slot->day) {
                $placedSlot = new TimeSlot(
                    $placed['day_of_week'],
                    $placed['start_time'],
                    $placed['duration_minutes'] / 60
                );
                if ($slot->overlaps($placedSlot)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Compter les blocs déjà placés pour une assignation
     */
    private function countPlacedBlocksForAssignation(int $assignationId): int
    {
        return count(array_filter($this->placedSchedules, function ($schedule) use ($assignationId) {
            return $schedule['assignation_id'] === $assignationId;
        }));
    }

    /**
     * Vérifier si un bloc est déjà placé
     */
    private function isBlockAlreadyPlaced(int $assignationId, int $blockIndex): bool
    {
        foreach ($this->placedSchedules as $schedule) {
            if ($schedule['assignation_id'] === $assignationId && 
                ($schedule['block_index'] ?? -1) === $blockIndex) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifier s'il y a un trou autour d'un créneau (heuristique)
     */
    private function hasGapAroundSlot(TimeSlot $slot): bool
    {
        // Vérifier s'il y a des cours avant ou après ce créneau le même jour
        foreach ($this->placedSchedules as $placed) {
            if ($placed['day_of_week'] === $slot->day) {
                $placedSlot = new TimeSlot(
                    $placed['day_of_week'],
                    $placed['start_time'],
                    $placed['duration_minutes'] / 60
                );
                
                // Vérifier si le créneau est adjacent (juste avant ou après)
                if ($placedSlot->end->eq($slot->start) || $slot->end->eq($placedSlot->start)) {
                    return false; // Pas de trou, il y a un cours adjacent
                }
            }
        }
        return true; // Il y a un trou
    }

    /**
     * Sauvegarder les créneaux placés en base de données
     */
    private function savePlacedSchedules(): void
    {
        foreach ($this->placedSchedules as $schedule) {
            // Retirer block_index avant la sauvegarde
            unset($schedule['block_index']);
            Schedule::create($schedule);
        }
    }

    /**
     * Trier les assignations par priorité de contraintes
     */
    private function sortAssignationsByPriority(): void
    {
        usort($this->assignations, function ($a, $b) {
            // Charger les sujets pour comparer
            $subjectA = \App\Models\Subject::find($a['subject_id']);
            $subjectB = \App\Models\Subject::find($b['subject_id']);

            // 1. Matières avec contrainte AVANT_PAUSE en premier (le plus restrictif)
            if ($subjectA && $subjectA->time_preference === TimePreference::AVANT_PAUSE->value) return -1;
            if ($subjectB && $subjectB->time_preference === TimePreference::AVANT_PAUSE->value) return 1;

            // 2. Matières avec contrainte SOIR ensuite
            if ($subjectA && $subjectA->time_preference === TimePreference::SOIR->value) return -1;
            if ($subjectB && $subjectB->time_preference === TimePreference::SOIR->value) return 1;

            // 3. Matières avec contrainte APRES_MIDI
            if ($subjectA && $subjectA->time_preference === TimePreference::APRES_MIDI->value) return -1;
            if ($subjectB && $subjectB->time_preference === TimePreference::APRES_MIDI->value) return 1;

            // 4. Matières avec contrainte MATIN
            if ($subjectA && $subjectA->time_preference === TimePreference::MATIN->value) return -1;
            if ($subjectB && $subjectB->time_preference === TimePreference::MATIN->value) return 1;

            // 5. Plus d'heures = plus prioritaire (plus difficile à placer)
            return ($b['weekly_hours'] ?? 0) <=> ($a['weekly_hours'] ?? 0);
        });
    }

    /**
     * Analyser les problèmes qui empêchent la génération
     */
    private function analyzeIssues(): array
    {
        $issues = [];
        
        foreach ($this->assignations as $assignationData) {
            $assignation = Assignation::with(['teacher', 'subject', 'classroom'])
                ->find($assignationData['id']);
            
            if (!$assignation) {
                continue;
            }
            
            $blocks = $assignation->calculateTimeBlocks();
            $totalHours = array_sum($blocks);
            
            // Vérifier la disponibilité de l'enseignant
            if (!$assignation->teacher->availability || empty($assignation->teacher->availability)) {
                $issues[] = "L'enseignant {$assignation->teacher->full_name} n'a pas de disponibilités définies";
                continue;
            }
            
            // Compter les créneaux disponibles pour cet enseignant
            $availableSlotsForTeacher = 0;
            foreach ($this->availableSlots as $slot) {
                if ($assignation->teacher->isAvailableAt(
                    $slot->day,
                    $slot->getStartTime(),
                    $slot->getEndTime()
                )) {
                    // Vérifier aussi la préférence horaire de la matière
                    if ($this->matchesTimePreference($assignation->subject, $slot)) {
                        $availableSlotsForTeacher++;
                    }
                }
            }
            
            if ($availableSlotsForTeacher < count($blocks)) {
                $issues[] = "Pas assez de créneaux disponibles pour {$assignation->subject->name} " .
                           "(enseignant: {$assignation->teacher->full_name}, " .
                           "nécessite: " . count($blocks) . " créneaux, " .
                           "disponibles: {$availableSlotsForTeacher})";
            }
            
            // Vérifier les contraintes strictes
            if ($assignation->subject->time_preference === TimePreference::AVANT_PAUSE->value) {
                $morningSlots = 0;
                foreach ($this->availableSlots as $slot) {
                    if ($slot->getTimePeriod() === 'morning' && 
                        $slot->start->hour < 9 || ($slot->start->hour === 9 && $slot->start->minute < 45)) {
                        $morningSlots++;
                    }
                }
                if ($morningSlots < count($blocks)) {
                    $issues[] = "Pas assez de créneaux avant 09h45 pour {$assignation->subject->name} " .
                               "(nécessite: " . count($blocks) . ", disponibles: {$morningSlots})";
                }
            }
        }
        
        // Vérifier le total d'heures vs créneaux disponibles
        $totalHoursNeeded = 0;
        foreach ($this->assignations as $assignationData) {
            $assignation = Assignation::find($assignationData['id']);
            if ($assignation) {
                $totalHoursNeeded += $assignation->weekly_hours;
            }
        }
        
        $totalSlotsAvailable = count($this->availableSlots);
        if ($totalHoursNeeded > $totalSlotsAvailable) {
            $issues[] = "Le nombre total d'heures ({$totalHoursNeeded}h) dépasse le nombre de créneaux disponibles ({$totalSlotsAvailable})";
        }
        
        return $issues;
    }
}

