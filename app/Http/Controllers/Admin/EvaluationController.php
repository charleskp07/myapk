<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluationRequests\StoreEvaluationRequest;
use App\Http\Requests\EvaluationRequests\UpdateEvaluationRequest;
use App\Interfaces\AssignationInterface;
use App\Interfaces\EvaluationInterface;
use App\Models\Bareme;
use App\Models\Breakdown;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{

    private AssignationInterface $assignationInterface;
    private EvaluationInterface $evaluationInterface;

    public function __construct(
        AssignationInterface $assignationInterface,
        EvaluationInterface $evaluationInterface,

    ) {
        $this->assignationInterface = $assignationInterface;
        $this->evaluationInterface = $evaluationInterface;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.evaluations.index", [
            'evaluations' => $this->evaluationInterface->index(),
            'page' => 'evaluations',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.evaluations.create", [
            'assignations' => $this->assignationInterface->index(),
            'baremes' => Bareme::all(),
            'breakdowns' => Breakdown::all(),
            'page' => 'evaluations',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEvaluationRequest $request)
    {
        $data = [
            'assignation_id' => $request->assignation_id,
            'bareme_id' => $request->bareme_id,
            'title' => $request->title,
            'date' => $request->date,
            'type' => $request->type,
            'breakdown_id' => $request->breakdown_id ? $request->breakdown_id  : 1,
        ];

        try {

            $this->evaluationInterface->store($data);

            return back()->with('success', "Evaluation créée avec succès !");
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
        return view("admin.evaluations.show", [
            'evaluation' => $this->evaluationInterface->show($id),
            'page' => 'evaluations',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("admin.evaluations.edit", [
            'evaluation' => $this->evaluationInterface->show($id),
            'assignations' => $this->assignationInterface->index(),
            'page' => 'evaluations',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvaluationRequest $request, string $id)
    {
        $data = [
            'assignation_id' => $request->assignation_id,
            'bareme_id' => $request->bareme_id,
            'title' => $request->title,
            'date' => $request->date,
            'type' => $request->type,
            'breakdown_id' => $request->breakdown_id,
        ];

        try {

            $this->evaluationInterface->update($data, $id);

            return back()->with('success', "Evaluation mis à jour avec succès !");
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
        $evaluation = Evaluation::find($id);

        if ($evaluation->notes()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette évaluation car elle contient des notes');
        }
        try {

            $this->evaluationInterface->destroy($id);

            return redirect()->route('classrooms.index')->with('success', "L'évaluation a été supprimée avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
}
