<?php

use App\Enums\PaymentTypeEnums;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->onDelete('cascade');
            $table->foreignIdFor(Fee::class)->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', [
                PaymentTypeEnums::ESPECES->value,
                PaymentTypeEnums::DEPOT->value,
                PaymentTypeEnums::AUTRE->value,
            ]);
            $table->date('payment_date');
            $table->string('reference')->unique();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
