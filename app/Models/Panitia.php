<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Panitia extends Model
{
    protected $table = 'panitias';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'email',
        'jabatan',
        'aktif',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if password matches
     */
    public function matchesPassword($password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Get panitia by username
     */
    public static function findByUsername($username)
    {
        return self::where('username', $username)->where('aktif', true)->first();
    }

    /**
     * Get panitia by email
     */
    public static function findByEmail($email)
    {
        return self::where('email', $email)->where('aktif', true)->first();
    }
}
