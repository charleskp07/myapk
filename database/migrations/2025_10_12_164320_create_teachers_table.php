<?php

use App\Enums\GenderEnums;
use App\Models\User;
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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('gender', [
                GenderEnums::MASCULIN->value,
                GenderEnums::FEMININ->value,
            ]);
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('nationality')->default('Togolaise');
            $table->string('speciality');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
