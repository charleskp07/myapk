<?php

use App\Enums\EvaluationTypeEnums;
use App\Models\Assignation;
use App\Models\Bareme;
use App\Models\Breakdown;
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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Assignation::class)->onDelete('cascade');
            $table->foreignIdFor(Bareme::class)->onDelete('cascade');
            $table->foreignIdFor(Breakdown::class)->onDelete('cascade');
            $table->string('title');
            $table->date('date');
            $table->enum('type', [
                EvaluationTypeEnums::INTERROGATION->value,
                EvaluationTypeEnums::DEVOIR->value,
                EvaluationTypeEnums::COMPOSITION->value,
            ]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
