<?php

namespace App\Repositories;

use App\Interfaces\ClassroomInterface;
use App\Models\Classroom;

class ClassroomRepository implements ClassroomInterface
{
    public function index()
    {
        return Classroom::all();
        // return Classroom::orderBy('name')->get();
    }

    public function store(array $data)
    {
        return Classroom::create($data);
    }

    public function show(string $id)
    {
        return Classroom::find($id);
    }

    public function update(array $data, string $id)
    {
        Classroom::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        Classroom::find($id)->delete();
    }
}
