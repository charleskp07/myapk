<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\RoleEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // 'username',
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function data(): HasOne
    {
        return $this->hasOne(UserData::class);
    }


    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function isAdmin()
    {
        return $this->role === RoleEnums::ADMIN->value;
    }

    public function isTeacher()
    {
        return $this->role === RoleEnums::TEACHER->value;
    }

    public function isStudent()
    {
        return $this->role === RoleEnums::STUDENT->value;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->name)) {
                $baseMame = Str::slug($user->name);
                $name = $baseMame;
                $count = 1;

                // Évite les doublons
                while (self::where('name', $name)->exists()) {
                    $name = $baseMame . $count;
                    $count++;
                }

                $user->name = $name;
            }
        });
    }
}
