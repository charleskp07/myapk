<?php

namespace App\Models;

use App\Enums\FeeTypeEnums;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'name',
        'amount',
        'type',
        'deadline',
    ];


    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Relation avec les élèves (via la classe)
     */
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,      // modèle final
            Classroom::class,    // modèle intermédiaire
            'id',                // clé primaire de Classroom
            'classroom_id',      // clé étrangère de Student
            'classroom_id',      // clé locale de Fee
            'id'                 // clé locale de Classroom
        );
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Vérifier si le frais est obligatoire
     */
    public function isMandatory(): bool
    {
        return $this->type === FeeTypeEnums::OBLIGATOIRE->value;
    }

    /**
     * Vérifier si le frais est optionnel
     */
    public function isOptional(): bool
    {
        return $this->type === FeeTypeEnums::OPTIONNEL->value;
    }

    public function getIsOverdueAttribute()
    {
        return $this->deadline && Carbon::now()->greaterThan(Carbon::parse($this->deadline));
    }


    public function getPenaltyAmountAttribute()
    {
        if (!$this->deadline) return 0;

        $weeksLate = Carbon::parse($this->deadline)->diffInWeeks(Carbon::now(), false);

        return $weeksLate > 0 ? $weeksLate * 500 : 0;
    }
}
