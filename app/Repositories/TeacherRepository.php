<?php

namespace App\Repositories;

use App\Interfaces\TeacherInterface;
use App\Models\Teacher;

class TeacherRepository implements TeacherInterface
{
     public function index()
    {
        return Teacher::all();
    }

    public function store(array $data)
    {
        return Teacher::create($data);
    }

    public function show(string $id)
    {
        return Teacher::find($id);
    }

    public function update(array $data, string $id)
    {
        Teacher::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        Teacher::find($id)->delete();
    }
}
