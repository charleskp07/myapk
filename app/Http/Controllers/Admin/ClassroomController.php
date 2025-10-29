<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequests\StoreClassroomRequest;
use App\Http\Requests\ClassroomRequests\UpdateClassroomRequest;
use App\Interfaces\ClassroomInterface;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{

    private ClassroomInterface $classroomInterface;

    public function __construct(
        ClassroomInterface $classroomInterface,
    ) {
        $this->classroomInterface = $classroomInterface;
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
        return view("admin.classrooms.show", [
            'classroom' => $this->classroomInterface->show($id),
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
}
