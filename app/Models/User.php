<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',    // penting untuk SSO
        'phone_number',
        'gender',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        public function perusahaan()
    {
        return $this->hasOne(Perusahaan::class, 'id_user');
    }

            public function dosen_penguji()
    {
        return $this->hasOne(dosen_penguji::class, 'id_user');
    }
}
