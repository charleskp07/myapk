<?php

namespace App\Repositories;

use App\Interfaces\FeeInterface;
use App\Models\Fee;

class FeeRepository implements FeeInterface
{
    public function index()
    {
        return Fee::all();
    }

    public function store(array $data)
    {
        return Fee::create($data);
    }

    public function show(string $id)
    {
        return Fee::find($id);
    }

    public function update(array $data, string $id)
    {
        Fee::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        Fee::find($id)->delete();
    }
}
