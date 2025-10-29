<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTPCode extends Model
{
    protected $fillable = [
        'email',
        'code',
    ];

    protected function casts(): array
    {
        return [
            'code' => 'hashed',
        ];
    }
}
