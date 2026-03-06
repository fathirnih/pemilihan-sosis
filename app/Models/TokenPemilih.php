<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class TokenPemilih extends Model
{
    use HasFactory;

    protected $table = 'token_pemilih';

    protected $fillable = [
        'periode_id',
        'pemilih_id',
        'kelas_id',
        'token',
        'token_hash',
        'status',
        'sudah_memilih',
        'digunakan_pada',
        'kadaluarsa_pada',
        'nama_pemilih',
        'nis_pemilih',
    ];

    protected $hidden = [
        'token_hash',
    ];

    protected function casts(): array
    {
        return [
            'sudah_memilih' => 'boolean',
            'digunakan_pada' => 'datetime',
            'kadaluarsa_pada' => 'datetime',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePemilihan::class, 'periode_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Pemilih::class, 'pemilih_id');
    }

    public function pemilih(): BelongsTo
    {
        return $this->belongsTo(Pemilih::class, 'pemilih_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function suara(): HasOne
    {
        return $this->hasOne(Suara::class, 'id');
    }

    /**
     * Get nama_pemilih with fallback to siswa.nama
     */
    public function getNamaPemilihAttribute($value)
    {
        return $value ?? ($this->siswa?->nama ?? 'N/A');
    }

    /**
     * Get nis_pemilih with fallback to siswa.nis
     */
    public function getNisPemilihAttribute($value)
    {
        return $value ?? ($this->siswa?->nisn ?? '-');
    }
}
