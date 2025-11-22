<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\BreakdownRequest;
use App\Models\Breakdown;
use Illuminate\Http\Request;

class BreakdownSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.settings.breakdowns.index', [
            'page' => 'settings',
            'breakdowns' => Breakdown::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settings.breakdowns.create',[
            'page' => 'settings',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BreakdownRequest $request)
    {
        $data = [
            'type' => $request->type,
            'value' => $request->value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        try {

            Breakdown::create($data);

            return back()->with('success', 'Découpage créé avec succès');
        } catch (\Throwable $th) {
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.settings.breakdowns.edit', [
            'page' => 'settings',
            'breakdown' => Breakdown::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BreakdownRequest $request, string $id)
    {
        $data = [
            'type' => $request->type,
            'value' => $request->value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        try {

            Breakdown::find($id)->update($data);

            return back()->with('success', 'Decoupage mise à jour avec succès');
        } catch (\Throwable $th) {
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
        $breakdown = Breakdown::find($id);

        if ($breakdown->evaluations()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce découpage car elle contient des évaluations');
        }
        try {

            $breakdown->destroy();

            return redirect()->route('admin.breakdown.index')->with('success', "Le découpage a été supprimée avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
}
