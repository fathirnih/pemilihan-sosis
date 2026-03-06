<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
    ];

    public function siswa(): HasMany
    {
        return $this->hasMany(Pemilih::class, 'kelas_id')
            ->where('jenis', 'siswa');
    }
}
