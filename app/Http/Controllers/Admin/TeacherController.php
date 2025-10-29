<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequests\StoreTeacherRequest;
use App\Http\Requests\TeacherRequests\UpdateTeacherRequest;
use App\Interfaces\TeacherInterface;
use App\Interfaces\UserInterface;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{


    private UserInterface $userInterface;
    private TeacherInterface $teacherInterface;

    public function __construct(
        UserInterface $userInterface,
        TeacherInterface $teacherInterface,

    ) {
        $this->userInterface = $userInterface;
        $this->teacherInterface = $teacherInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.teachers.index", [
            'teachers' => $this->teacherInterface->index(),
            'page' => 'teachers',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.teachers.create", [
            'page' => 'teachers',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        $data = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone ? $request->phone : 'Non renseigné',
            'role' => RoleEnums::TEACHER->value,
            'password' => '12345678',
        ];

        try {

            $user = $this->userInterface->store($data);
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }


        $file = $request->file('photo');
        if ($file)
            $path = $file->store('teachers', 'public');

        $data = [
            'user_id' => $user->id,
            'photo' => $file ? $path : null,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'nationality' => $request->nationality ? $request->nationality : "Togolaise",
            'speciality' => $request->speciality,
        ];

        try {

            $this->teacherInterface->store($data);

            return back()->with('success', "enseignant(e) ajouté(e) avec succès !");
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
        return view("admin.teachers.show", [
            'teacher' => $this->teacherInterface->show($id),
            'page' => 'teachers',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("admin.teachers.edit", [
            'teacher' => $this->teacherInterface->show($id),
            'page' => 'teachers',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, string $id)
    {

        $teacher = Teacher::find($id);

        $file = $request->file('photo');
        if ($file)
            $path = $file->store('teachers', 'public');

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'nationality' => $request->nationality ? $request->nationality : $teacher->nationality,
            'speciality' => $request->speciality,
        ];

        if (isset($path))
            $data['photo'] = $path;

        try {

            $this->teacherInterface->update($data, $id);

            return back()->with('success', "enseignant(e) mis à jour avec succès !");
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

        $teacher = Teacher::find($id);

        $user_id = $teacher->user_id;

        try {

            $this->teacherInterface->destroy($id);
            $this->userInterface->destroy($user_id);

            return back()->with('success', "enseignant(e) supprimé(e) avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
}
