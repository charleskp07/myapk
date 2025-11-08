<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeeRequest;
use App\Interfaces\ClassroomInterface;
use App\Interfaces\FeeInterface;
use App\Models\Classroom;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    private ClassroomInterface $classroomInterface;
    private FeeInterface $feeInterface;

    public function __construct(
        ClassroomInterface $classroomInterface,
        FeeInterface $feeInterface,
    ) {
        $this->classroomInterface = $classroomInterface;
        $this->feeInterface = $feeInterface;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $classroom = Classroom::find($request->classroom_id);
        return view('admin.fees.create', [
            'classrooms' => $this->classroomInterface->index(),
            'classroom_id' => $request->classroom_id,
            'classroom' => $classroom,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeeRequest $request)
    {
        $data = [
            'classroom_id' => $request->classroom_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'type' => $request->type,
        ];
        
        try {
            
            $this->feeInterface->store($data);

            return back()->with('success', "Frais ajouté avec succès !");

        } catch (\Exception $ex) {
            // return $ex;
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("admin.fees.edit", [
            'classrooms' => $this->classroomInterface->index(),
            'fee' => $this->feeInterface->show($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeeRequest $request, string $id)
    {
        $data = [
            'classroom_id' => $request->classroom_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'type' => $request->type,
        ];

        try {

            $this->feeInterface->update($data, $id);

            return back()->with('success', "Frais mise à jour avec succès !");
        } catch (\Exception $ex) {
            // return $ex;
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
        //
    }

}
