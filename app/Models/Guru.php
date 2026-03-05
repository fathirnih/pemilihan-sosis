<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guru extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'guru';

    protected $fillable = [
        'nip',
        'nama',
        'password',
        'harus_ganti_kata_sandi',
        'aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'harus_ganti_kata_sandi' => 'boolean',
            'aktif' => 'boolean',
        ];
    }
}
