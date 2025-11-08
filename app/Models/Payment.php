<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'fee_id',
        'amount',
        'payment_method',
        'payment_date',
        'reference',
        'note',
    ];

    // protected static function booted()
    // {
    //     static::creating(function ($payment) {
    //         // Générer la référence automatiquement si elle n'existe pas
    //         if (!$payment->reference) {
    //             $lastPayment = Payment::latest()->first();
    //             $nextId = $lastPayment ? $lastPayment->id + 1 : 1;
    //             $payment->reference = 'PAY-' . now()->format('Ym') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    //         }
    //     });
    // }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }
}
