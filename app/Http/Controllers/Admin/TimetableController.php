<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Repositories\TimetableGeneratorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimetableController extends Controller
{


    private TimetableGeneratorRepository $timetableGeneratorInterface;

    public function __construct(

        TimetableGeneratorRepository $timetableGeneratorInterface,

    ) {
        $this->timetableGeneratorInterface = $timetableGeneratorInterface;
    }

    /**
     * Afficher la page de l'emploi du temps
     */
    public function index(Request $request)
    {
        $classrooms = Classroom::with('schedules')->get();
        $selectedClassroom = $request->get('classroom_id');

        return view('timetable.calendar', [
            'classrooms' => $classrooms,
            'selectedClassroom' => $selectedClassroom,
        ]);
    }

    /**
     * Générer l'emploi du temps pour une classe
     * POST /timetable/generate
     */
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Classe invalide',
                'errors' => $validator->errors(),
            ], 422);
        }

        $classroomId = $request->input('classroom_id');

        try {
            $result = $this->timetableGeneratorInterface->generate($classroomId);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les événements d'emploi du temps pour FullCalendar
     * GET /timetable/events
     */
    public function events(Request $request)
    {
        $classroomId = $request->get('classroom_id');
        $teacherId = $request->get('teacher_id');

        $query = Schedule::with(['teacher', 'classroom', 'subject'])
            ->where('is_active', true);

        if ($classroomId) {
            $query->where('classroom_id', $classroomId);
        }

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        $schedules = $query->get();

        $events = $schedules->map(function ($schedule) {
            return $schedule->toFullCalendarEvent();
        });

        return response()->json($events);
    }

    /**
     * Supprimer l'emploi du temps d'une classe
     * DELETE /timetable/{classroom_id}
     */
    public function destroy($classroomId)
    {
        try {
            $classroom = Classroom::findOrFail($classroomId);

            Schedule::where('classroom_id', $classroomId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Emploi du temps supprimé avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exporter l'emploi du temps en PDF (optionnel)
     */
    public function export($classroomId)
    {
        // TODO: Implémenter l'export PDF avec une librairie comme DomPDF
    }
}
