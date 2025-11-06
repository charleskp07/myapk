<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteAppreciationRequest;
use App\Models\Bareme;
use App\Models\NoteAppreciation;
use Illuminate\Http\Request;

class AppreciationSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.settings.notes.appreciations.index', [
            'baremes' => NoteAppreciation::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $bareme = Bareme::find($request->bareme_id);
        return view('admin.settings.notes.appreciations.create', [
            'baremes' => Bareme::all(),
            'bareme_id' => $request->bareme_id,
            'bareme' => $bareme,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteAppreciationRequest $request)
    {
        $data = [
            'bareme_id' => $request->bareme_id,
            'appreciation' => $request->appreciation,
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
        ];

        try {

            $noteAppreciation = NoteAppreciation::create($data);

            return redirect()
                ->route('admin.baremes.show', $noteAppreciation->bareme_id)
                ->with('success', 'Appréciation créée avec succès !');
        } catch (\Throwable $th) {
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
        return view('admin.settings.notes.appreciations.edit', [
            'baremes' => Bareme::all(),
            'appreciation' => NoteAppreciation::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $noteAppreciation = NoteAppreciation::find($id);

        $data = [
            'bareme_id' => $request->bareme_id,
            'appreciation' => $request->appreciation,
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
        ];

        try {

            $noteAppreciation->update($data);

            return redirect()
                ->route('admin.baremes.show', $noteAppreciation->bareme_id)
                ->with('success', 'Appréciation mis à jour avec succès !');

        } catch (\Throwable $th) {
            // return $th;
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

            NoteAppreciation::find($id)->destroy();

            return back()->with('success', "appreciation a été supprimée avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
}
