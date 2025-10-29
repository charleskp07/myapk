<?php

namespace App\Repositories;

use App\Interfaces\SubjectInterface;
use App\Models\Subject;

class SubjectRepository implements SubjectInterface
{
    public function index()
    {
        return Subject::all();
        // return Subject::orderBy('name')->get();
    }

    public function store(array $data)
    {
        return Subject::create($data);
    }

    public function show(string $id)
    {
        return Subject::find($id);
    }

    public function update(array $data, string $id)
    {
        Subject::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        Subject::find($id)->delete();
    }
}
