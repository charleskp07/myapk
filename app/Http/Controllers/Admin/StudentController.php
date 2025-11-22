<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequests\StoreStudentRequest;
use App\Http\Requests\StudentRequests\UpdateStudentRequest;
use App\Interfaces\ClassroomInterface;
use App\Interfaces\NoteInterface;
use App\Interfaces\StudentInterface;
use App\Interfaces\UserInterface;
use App\Models\Breakdown;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private UserInterface $userInterface;
    private ClassroomInterface $classroomInterface;
    private StudentInterface $studentInterface;
    private NoteInterface $noteInterface;

    public function __construct(
        UserInterface $userInterface,
        ClassroomInterface $classroomInterface,
        StudentInterface $studentInterface,
        NoteInterface $noteInterface,

    ) {
        $this->userInterface = $userInterface;
        $this->classroomInterface = $classroomInterface;
        $this->studentInterface = $studentInterface;
        $this->noteInterface = $noteInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.students.index", [
            'students' => $this->studentInterface->index(),
            'page' => 'students',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $classroom = Classroom::find($request->classroom_id);
        return view("admin.students.create", [
            'classrooms' => $this->classroomInterface->index(),
            'classroom_id' => $request->classroom_id,
            'classroom' => $classroom,
            'page' => 'students',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {

        // $data = [
        //     'name' => $request->first_name . ' ' . $request->last_name,
        //     'email' => $request->email,
        //     'phone' => $request->phone ? $request->phone : 'Non renseigné',
        //     'role' => RoleEnums::STUDENT->value,
        //     'password' => '12345678',
        // ];

        // try {

        //     $user = $this->userInterface->store($data);
        // } catch (\Exception $ex) {
        //     // return $ex;
        //     return back()->withErrors([
        //         'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
        //     ])->withInput();
        // }


        $file = $request->file('photo');
        if ($file)
            $path = $file->store('students', 'public');

        $data = [
            // 'user_id' => $user->id,
            'classroom_id' => $request->classroom_id,
            'photo' => $file ? $path : null,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone ? $request->phone : "Non renseigné",
            'nationality' => $request->nationality ? $request->nationality : "Togolaise",
        ];


        try {

            $this->studentInterface->store($data);

            return back()->with('success', "apprenant(e) ajouté(e) avec succès !");
        } catch (\Exception $ex) {
            // return $ex;
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


        $student = Student::with('notes.evaluation.breakdown')->findOrFail($id);

        // On récupère uniquement les découpages des évaluations de cet élève
        $breakdowns = $student->notes
            ->pluck('evaluation.breakdown')
            ->unique('id')
            ->values();

        return view("admin.students.show", [
            'student' => $this->studentInterface->show($id),
            'breakdowns' => $breakdowns,
            'page' => 'students',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("admin.students.edit", [
            'classrooms' => $this->classroomInterface->index(),
            'student' => $this->studentInterface->show($id),
            'page' => 'students',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, string $id)
    {

        $student = Student::find($id);

        $file = $request->file('photo');
        if ($file)
            $path = $file->store('students', 'public');

        $data = [
            'classroom_id' => $request->classroom_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone ? $request->phone : "Non renseigné",
            'nationality' => $request->nationality ? $request->nationality : $student->nationality,
        ];

        if (isset($path))
            $data['photo'] = $path;

        try {

            $this->studentInterface->update($data, $id);

            return back()->with('success', "apprenant(e) mis à jour avec succès !");
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

        $student = Student::find($id);

        $user_id = $student->user_id;

        if ($student->notes()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet(te) apprenant(e) car elle est liés à des notes');
        }

        try {

            $this->studentInterface->destroy($id);
            $this->userInterface->destroy($user_id);

            return back()->with('success', "apprenant(e) supprimé(e) avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
    
}
