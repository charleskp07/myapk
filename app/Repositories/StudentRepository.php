<?php

namespace App\Repositories;

use App\Interfaces\StudentInterface;
use App\Models\Student;

class StudentRepository implements StudentInterface
{
    public function index()
    {
        return Student::all();
    }

    public function store(array $data)
    {
        return Student::create($data);
    }

    public function show(string $id)
    {
        return Student::find($id);
    }

    public function update(array $data, string $id)
    {
        Student::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        Student::find($id)->delete();
    }
}
