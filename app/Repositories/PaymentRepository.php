<?php

namespace App\Repositories;

use App\Interfaces\PaymentInterface;
use App\Models\Payment;

class PaymentRepository implements PaymentInterface
{
    public function index()
    {
        return Payment::all();
        // return Payment::orderBy('name')->get();
    }

    public function store(array $data)
    {
        return Payment::create($data);
    }

    public function show(string $id)
    {
        return Payment::find($id);
    }

    public function update(array $data, string $id)
    {
        Payment::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        Payment::find($id)->delete();
    }
}
