<?php

namespace App\Repositories;

use App\Interfaces\EvaluationInterface;
use App\Models\Evaluation;

class EvaluationRepository implements EvaluationInterface
{
    public function index()
    {
        return Evaluation::all();
        // return Evaluation::orderBy('name')->get();
    }

    public function store(array $data)
    {
        return Evaluation::create($data);
    }

    public function show(string $id)
    {
        return Evaluation::find($id);
    }

    public function update(array $data, string $id)
    {
        Evaluation::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        Evaluation::find($id)->delete();
    }
}
