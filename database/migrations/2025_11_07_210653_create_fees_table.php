<?php

use App\Enums\FeeTypeEnums;
use App\Models\Classroom;
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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Classroom::class)->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 10, 2); 
            $table->enum('type', [
                FeeTypeEnums::OBLIGATOIRE->value,
                FeeTypeEnums::OPTIONNEL->value,
            ])->default(FeeTypeEnums::OBLIGATOIRE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
