<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequests\StoreClassroomRequest;
use App\Http\Requests\ClassroomRequests\UpdateClassroomRequest;
use App\Interfaces\ClassroomInterface;
use App\Interfaces\TeacherInterface;
use App\Models\Breakdown;
use App\Models\Classroom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{

    private ClassroomInterface $classroomInterface;
    private TeacherInterface $teacherInterface;


    public function __construct(
        ClassroomInterface $classroomInterface,
        TeacherInterface $teacherInterface,
    ) {
        $this->classroomInterface = $classroomInterface;
        $this->teacherInterface = $teacherInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.classrooms.index", [
            'classrooms' => $this->classroomInterface->index(),
            'page' => 'classrooms',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.classrooms.create", [
            'teachers' => $this->teacherInterface->index(),
            'page' => 'classrooms',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassroomRequest $request)
    {
        $data = [
            'level' => $request->level,
            'name' => $request->name,
            'section' => $request->section,
            'teacher_id' => $request->teacher_id,
        ];

        try {

            $this->classroomInterface->store($data);

            return back()->with('success', "La classe a été créée avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $classroom = Classroom::with(['students.notes.evaluation.breakdown'])->findOrFail($id);

        $breakdownIds = $classroom->students->flatMap(function ($student) {
            return $student->notes->pluck('evaluation.breakdown_id');
        })->unique()->values();

        $breakdowns = Breakdown::whereIn('id', $breakdownIds)->get();

        return view("admin.classrooms.show", [
            'classroom' => $classroom,
            'breakdowns' => $breakdowns,
            'page' => 'classrooms',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("admin.classrooms.edit", [
            'classroom' => $this->classroomInterface->show($id),
            'teachers' => $this->teacherInterface->index(),
            'page' => 'classrooms',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassroomRequest $request, string $id)
    {
        $data = [
            'level' => $request->level,
            'name' => $request->name,
            'section' => $request->section,
            'teacher_id' => $request->teacher_id,
        ];

        try {

            $this->classroomInterface->update($data, $id);

            return back()->with('success', "La classe a été mise à jour avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $classroom = Classroom::find($id);

        if ($classroom->students()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette classe car elle contient des étudiants');
        }
        try {

            $this->classroomInterface->destroy($id);

            return redirect()->route('classrooms.index')->with('success', "La classe a été supprimée avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }


    public function statsData(Request $request, $id)
    {
        $classroom = Classroom::with('students.notes.evaluation')->findOrFail($id);
        $breakdownId = $request->breakdown_id;

        $students = $classroom->students;

        $states = [
            'excellent' => 0,
            'tres_bien' => 0,
            'bien' => 0,
            'assez_bien' => 0,
            'passable' => 0,
            'insuffisant' => 0,
            'mediocre' => 0,
        ];

        foreach ($students as $student) {
            $notes = $student->notes()->whereHas('evaluation', fn($q) => $q->where('breakdown_id', $breakdownId))
                ->with('evaluation.bareme', 'evaluation.assignation')->get();

            $totalWeighted = 0;
            $totalCoeff = 0;

            foreach ($notes->groupBy(fn($n) => $n->evaluation->assignation->subject_id) as $subjectNotes) {
                $coeff = $subjectNotes->first()->evaluation->assignation->coefficient ?? 1;

                $noteFinale = $subjectNotes->map(function ($n) {
                    $bareme = $n->evaluation->bareme->value ?? 20;
                    return ($n->value / $bareme) * 20;
                })->avg();

                $totalWeighted += $noteFinale * $coeff;
                $totalCoeff += $coeff;
            }

            $avg = $totalCoeff > 0 ? $totalWeighted / $totalCoeff : 0;

            // Comptage mentions
            if ($avg >= 19) $states['excellent']++;
            elseif ($avg >= 16) $states['tres_bien']++;
            elseif ($avg >= 14) $states['bien']++;
            elseif ($avg >= 12) $states['assez_bien']++;
            elseif ($avg >= 10) $states['passable']++;
            elseif ($avg >= 5) $states['insuffisant']++;
            else $states['mediocre']++;
        }

        return response()->json([
            'labels' => ['Excellent', 'Très bien', 'Bien', 'Assez-bien', 'Passable', 'Insuffisant', 'Médiocre'],
            'data' => array_values($states),
        ]);
    }

    public function exportStudentsListPdf(Request $request) 
    {

        $classroom = Classroom::find($request->classroom_id);
        
        $pdf = Pdf::loadView('admin.pdf.students.list', [
            'classroom' => $classroom,
        ]);

        return $pdf->stream("Bulletin_{$classroom->name}_{$classroom->section}.pdf");

    }
}
