<?php

use App\Enums\ClassroomLevelEnums;
use App\Models\Teacher;
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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Teacher::class)->unique()->nullable();
            $table->enum('level', [
                ClassroomLevelEnums::COLLEGE->value,
                ClassroomLevelEnums::LYCEE->value,
            ]);
            $table->string('name');
            $table->string('section');
            $table->timestamps();

            $table->index(['level', 'name']);
            $table->index('section');


            // $table->unique(['level', 'name', 'section']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
