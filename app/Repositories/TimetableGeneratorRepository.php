<?php

namespace App\Repositories;

use App\Services\TimetableGeneratorService;
use Illuminate\Support\Facades\Log;

/**
 * Repository pour la génération d'emploi du temps
 * Délègue la logique au service TimetableGeneratorService
 */
class TimetableGeneratorRepository
{
    private TimetableGeneratorService $timetableGeneratorService;

    public function __construct(TimetableGeneratorService $timetableGeneratorService)
    {
        $this->timetableGeneratorService = $timetableGeneratorService;
    }

    /**
     * Générer l'emploi du temps pour une classe donnée
     */
    public function generate(int $classroomId): array
    {
        try {
            return $this->timetableGeneratorService->generate($classroomId);
        } catch (\Exception $e) {
            Log::error('Erreur génération emploi du temps (Repository): ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'schedules' => [],
            ];
        }
    }
}
