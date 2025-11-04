<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\PaymentInterface;
use Illuminate\Http\Request;

class PaymentController extends Controller
{


    private PaymentInterface $paymentInterface;

    public function __construct(
        PaymentInterface $paymentInterface,

    ) {
        $this->paymentInterface = $paymentInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.payments.index', [
            'payments' => $this->paymentInterface->index(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
