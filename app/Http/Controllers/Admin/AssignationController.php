<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignationRequests\StoreAssignationRequest;
use App\Http\Requests\AssignationRequests\UpdateAssignationRequest;
use App\Interfaces\AssignationInterface;
use App\Interfaces\ClassroomInterface;
use App\Interfaces\SubjectInterface;
use App\Interfaces\TeacherInterface;
use App\Models\Assignation;
use Illuminate\Http\Request;

class AssignationController extends Controller
{


    private ClassroomInterface $classroomInterface;
    private TeacherInterface $teacherInterface;
    private SubjectInterface $subjectInterface;
    private AssignationInterface $assignationInterface;

    public function __construct(
        ClassroomInterface $classroomInterface,
        TeacherInterface $teacherInterface,
        SubjectInterface $subjectInterface,
        AssignationInterface $assignationInterface,

    ) {
        $this->classroomInterface = $classroomInterface;
        $this->teacherInterface = $teacherInterface;
        $this->subjectInterface = $subjectInterface;
        $this->assignationInterface = $assignationInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.assignations.index", [
            'assignations' => $this->assignationInterface->index(),
            'page' => 'assignations',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.assignations.create", [
            'classrooms' => $this->classroomInterface->index(),
            'teachers' => $this->teacherInterface->index(),
            'subjects' => $this->subjectInterface->index(),
            'page' => 'assignations',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignationRequest $request)
    {

        $data = [
            'classroom_id' => $request->classroom_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'coefficient' => $request->coefficient,
        ];

        try {

            $this->assignationInterface->store($data);

            return back()->with('success', "assignation ajoutée avec succès !");
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
        return view("admin.assignations.show", [
            'assignation' => $this->assignationInterface->show($id),
            'page' => 'assignations',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("admin.assignations.edit", [
            'assignation' => $this->assignationInterface->show($id),
            'classrooms' => $this->classroomInterface->index(),
            'teachers' => $this->teacherInterface->index(),
            'subjects' => $this->subjectInterface->index(),
            'page' => 'assignations',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignationRequest $request, string $id)
    {
        $data = [
            'classroom_id' => $request->classroom_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'coefficient' => $request->coefficient,
        ];

        try {

            $this->assignationInterface->update($data, $id);

            return back()->with('success', "assignation mis à jour avec succès !");
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
        try {

            $this->assignationInterface->destroy($id);

            return back()->with('success', "assignation supprimée avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
}
