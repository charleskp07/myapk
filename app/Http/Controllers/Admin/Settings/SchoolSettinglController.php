<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Interfaces\SchoolSettingInterface;
use Illuminate\Http\Request;

class SchoolSettinglController extends Controller
{

    private SchoolSettingInterface $schoolsettingInterface;

    public function __construct(
        SchoolSettingInterface $schoolsettingInterface,
    ) {
        $this->schoolsettingInterface = $schoolsettingInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.settings.schoolsettings.index", [
            'schoolsettings' => $this->schoolsettingInterface->index(),
            'page' => 'settings',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.settings.schoolsettings.create", [
            'page' => 'settings',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $file = $request->file('logo');
        if ($file)
            $path = $file->store('schoolsetting', 'public');

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'logo' => $file ? $path : null,
            'principal' => $request->principal,
            'academic_year' => $request->academic_year,
        ];

        try {

            $this->schoolsettingInterface->store($data);

            return  redirect()->route('admin.schoolsetting.index')->with('success', "Le paramètre a été créé avec succès !");
        } catch (\Exception $ex) {
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
        return view("admin.settings.schoolsettings.edit", [
            'schoolsetting' => $this->schoolsettingInterface->show($id),
            'page' => 'settings',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $file = $request->file('logo');
        if ($file)
            $path = $file->store('schoolsetting', 'public');

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'principal' => $request->principal,
            'academic_year' => $request->academic_year,
        ];

        if (isset($path))
            $data['logo'] = $path;

        try {

            $this->schoolsettingInterface->update($data, $id);

            return  redirect()->route('admin.schoolsetting.index')->with('success', "La parametrè a été mise à jour avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
}
