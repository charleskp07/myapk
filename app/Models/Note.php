<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'student_id',
        'note_appreciation_id',
        'value',
        'comment',
    ];

    protected $casts = [
        'value' => 'float',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }
    
    public function appreciation(): BelongsTo
    {
        return $this->belongsTo(NoteAppreciation::class, 'note_appreciation_id');
    }


    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
