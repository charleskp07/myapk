<?php

namespace App\Repositories;

use App\Interfaces\AssignationInterface;
use App\Models\Assignation;

class AssignationRepository implements AssignationInterface
{
    public function index()
    {
        return Assignation::all();
        // return Assignation::orderBy('name')->get();
    }

    public function store(array $data)
    {
        return Assignation::create($data);
    }

    public function show(string $id)
    {
        return Assignation::find($id);
    }

    public function update(array $data, string $id)
    {
        Assignation::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        Assignation::find($id)->delete();
    }
}
