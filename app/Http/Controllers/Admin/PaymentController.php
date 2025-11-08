<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\PaymentInterface;
use App\Interfaces\StudentInterface;
use App\Models\Payment;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentController extends Controller
{


    private PaymentInterface $paymentInterface;
    private StudentInterface $studentInterface;

    public function __construct(
        PaymentInterface $paymentInterface,
        StudentInterface $studentInterface,
    ) {
        $this->paymentInterface = $paymentInterface;
        $this->studentInterface = $studentInterface;
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
    public function create(Request $request)
    {
        $student = Student::find($request->student_id);

        $classroom = $student ? $student->classroom : collect();

        $fees = $classroom ? $classroom->fees : collect();

        return view('admin.payments.create', [
            'students' => $this->studentInterface->index(),
            'student_id' => $request->student_id,
            'student' => $student,
            'fees' => $fees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'student_id' => $request->student_id,
            'fee_id' => $request->fee_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'note' => $request->note,
        ];

        $lastPayment = Payment::latest()->first();
        $nextId = $lastPayment ? $lastPayment->id + 1 : 1;
        $data['reference'] = 'PAY-' . now()->format('Ym') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        try {

            $payment= $this->paymentInterface->store($data);

            // return back()->with('success', "Paiement ajouté avec succès !");

            return redirect()->route('admin.payments.receipt', $payment->id)
                ->with('success', 'Paiement ajouté avec succès !');
        } catch (\Exception $ex) {
            // return $ex;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.payments.edit', [
            'payment' => $this->paymentInterface->show($id),
            'students' => $this->studentInterface->index(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'reference' => $request->reference,
            'note' => $request->note,
        ];

        try {

            $this->paymentInterface->update($data, $id);

            return back()->with('success', "Paiement mis à jour avec succès !");
        } catch (\Exception $ex) {
            // return $ex;
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
        //
    }


    public function receipt($id)
    {
        $payment = Payment::with(['student', 'fee'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.pdf.payments.receipt', compact('payment'));
        return $pdf->stream('recu_' . $payment->reference . '.pdf');
    }
}
