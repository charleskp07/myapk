<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Interfaces\EvaluationInterface;
use App\Interfaces\NoteInterface;
use App\Models\Evaluation;
use App\Models\NoteAppreciation;
use Illuminate\Http\Request;

class NoteController extends Controller
{


    private NoteInterface $noteInterface;
    private EvaluationInterface $evaluationInterface;

    public function __construct(
        NoteInterface $noteInterface,
        EvaluationInterface $evaluationInterface,

    ) {
        $this->noteInterface = $noteInterface;
        $this->evaluationInterface = $evaluationInterface;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $evaluation = Evaluation::find($request->evaluation_id);
        return view('admin.notes.create', [
            'evaluations' =>  $this->evaluationInterface->index(),
            'evaluation_id' => $request->evaluation_id,
            'evaluation' => $evaluation,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request)
    {

        $appreciation = NoteAppreciation::where('min_value', '<=', $request->value)
            ->where('max_value', '>=', $request->value)
            ->first();

        if (!$appreciation) {
            return back()->withErrors(['error' => 'aucune appreciation n\'a ete trouvé']);
            // continue;
        }

        $data = [
            'evaluation_id' => $request->evaluation_id,
            'note_appreciation_id' => $appreciation?->id,
            'students' => $request->students,
        ];

        try {

            $this->noteInterface->store($data);

            return back()->with('success', "Notes remplies avec succès !");
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
        return view("admin.notes.edit", [
            'evaluation' => $this->evaluationInterface->show($id),

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, string $id)
    {

        $appreciation = NoteAppreciation::where('min_value', '<=', $request->value)
            ->where('max_value', '>=', $request->value)
            ->first();

        if (!$appreciation) {
            return back()->withErrors(['error' => 'aucune appreciation n\'a ete trouvé']);
            // continue;
        }


        $data = [
            'evaluation_id' => $request->evaluation_id,
            'note_appreciation_id' => $appreciation?->id,
            'students' => $request->students,
        ];

        try {

            $this->noteInterface->update($data);

            return back()->with('success', "Notes mise à jour avec succès !");
        } catch (\Exception $ex) {
            return $ex;
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
