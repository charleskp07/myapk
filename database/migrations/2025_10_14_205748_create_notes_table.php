<?php

use App\Models\Evaluation;
use App\Models\NoteAppreciation;
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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Evaluation::class)->onDelete('cascade');
            $table->foreignIdFor(Student::class)->onDelete('cascade');
            $table->foreignIdFor(NoteAppreciation::class);
            $table->float('value');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
