<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Bareme;
use Illuminate\Http\Request;

class BaremeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('admin.settings.notes.baremes.index', [
            'baremes' => Bareme::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settings.notes.baremes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'value' => $request->value,
        ];

        try {

            Bareme::create($data);

            return back()->with('success', 'Barème créé avec succès');
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
        return view('admin.settings.notes.baremes.show', [
            'bareme' => Bareme::find($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.settings.notes.baremes.edit', [
            'bareme' => Bareme::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'value' => $request->value,
        ];

        try {

            Bareme::find($id)->update($data);

            return back()->with('success', 'Barème mise à jour avec succès');
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
        $bareme = Bareme::find($id);

        if ($bareme->noteAppreciations()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet barème car elle contient des appréciations');
        }
        try {

            $bareme->destroy();

            return redirect()->route('admin.baremes.index')->with('success', "La barème a été supprimée avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
}
