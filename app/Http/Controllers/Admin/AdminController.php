<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\ClassroomInterface;
use App\Interfaces\StudentInterface;
use App\Interfaces\TeacherInterface;
use App\Models\Classroom;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    private ClassroomInterface $classroomInterface;
    private StudentInterface $studentInterface;
    private TeacherInterface $teacherInterface;

    public function __construct(
        ClassroomInterface $classroomInterface,
        StudentInterface $studentInterface,
        TeacherInterface $teacherInterface,

    ) {
        $this->classroomInterface = $classroomInterface;
        $this->studentInterface = $studentInterface;
        $this->teacherInterface = $teacherInterface;
    }


    public function dashboard()
    {

        $students = $this->studentInterface->index();

        $maleCount = $students->where('gender', 'Masculin')->count();

        $femaleCount = $students->where('gender', 'Féminin')->count();

        $total = $maleCount + $femaleCount;

        $malePercentage = $total > 0 ? round(($maleCount / $total) * 100, 2) : 0;

        $femalePercentage = $total > 0 ? round(($femaleCount / $total) * 100, 2) : 0;

        $totalCollected = Payment::sum('amount');

        $totalExpected = 0;

        $classrooms = Classroom::with('fees', 'students')->get();

        foreach ($classrooms as $classroom) {
            $studentCount = $classroom->students->count(); // nombre d'élèves dans la classe

            foreach ($classroom->fees as $fee) {
                $totalExpected += $fee->amount * $studentCount;
            }
        }



        return view("admin.dashboard", [
            'page' => 'dashboard',
            'classrooms' => $this->classroomInterface->index(),
            'teachers' => $this->teacherInterface->index(),
            'students' => $students,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'malePercentage' => $malePercentage,
            'femalePercentage' => $femalePercentage,
            'totalCollected' => $totalCollected,
            'totalExpected' => $totalExpected,
        ]);
    }
}
