<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'email',
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
     * Get admin by username
     */
    public static function findByUsername($username)
    {
        return self::where('username', $username)->where('aktif', true)->first();
    }

    /**
     * Get admin by email
     */
    public static function findByEmail($email)
    {
        return self::where('email', $email)->where('aktif', true)->first();
    }
}
