<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'classroom_id',
        'photo',
        'first_name',
        'last_name',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'email',
        'phone',
        'nationality',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(classroom::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return Carbon::parse($this->date_of_birth)->age;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : asset('images/default-avatar.png');
    }


}
