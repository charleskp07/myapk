<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequests\StoreSubjectRequest;
use App\Http\Requests\SubjectRequests\UpdateSubjectRequest;
use App\Interfaces\SubjectInterface;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    private SubjectInterface $subjectInterface;

    public function __construct(
        SubjectInterface $subjectInterface,
    ) {
        $this->subjectInterface = $subjectInterface;
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.subjects.index", [
            'subjects' => $this->subjectInterface->index(),
            'page' => 'subjects',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.subjects.create", [
            'page' => 'subjects',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        try {

            $this->subjectInterface->store($data);

            return back()->with('success', "La Matière a été créée avec succès !");
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
        // return view("", [
        //     'subject' => $this->subjectInterface->show($id),
        //     'page' => 'subjects',
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("admin.subjects.edit", [
            'subject' => $this->subjectInterface->show($id),
            'page' => 'subjects',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, string $id)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        try {

            $this->subjectInterface->update($data, $id);

            return back()->with('success', "La Matière a été mise à jour avec succès !");
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
        $subject = Subject::find($id);

        if ($subject->assignations()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette Matière car elle est liés à des assignations');
        }
        
        try {

            $this->subjectInterface->destroy($id);

            return redirect()->route('subjects.index')->with('success', "La Matière a été supprimée avec succès !");
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }
}
