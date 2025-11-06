<?php

use App\Enums\NoteAppreciationEnums;
use App\Models\Bareme;
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
        Schema::create('note_appreciations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bareme::class)->onDelete('cascade');
            $table->enum('appreciation', [
                NoteAppreciationEnums::MEDIOCRE->value,
                NoteAppreciationEnums::INSUFFISANT->value,
                NoteAppreciationEnums::PASSABLE->value,
                NoteAppreciationEnums::ASSEZ_BIEN->value,
                NoteAppreciationEnums::BIEN->value,
                NoteAppreciationEnums::TRES_BIEN->value,
                NoteAppreciationEnums::EXCELLENT->value,
            ]);
            $table->float('min_value');
            $table->float('max_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_appreciations');
    }
};
