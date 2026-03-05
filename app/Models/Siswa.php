<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama',
        'kelas_id',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kandidatAnggota(): HasMany
    {
        return $this->hasMany(KandidatAnggota::class, 'siswa_id');
    }

    public function tokenPemilih(): HasMany
    {
        return $this->hasMany(TokenPemilih::class, 'pemilih_id')
            ->where('tipe_pemilih', 'siswa');
    }
}
