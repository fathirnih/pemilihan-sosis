<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemilih extends Model
{
    use HasFactory;

    protected $table = 'pemilih';

    protected $fillable = [
        'nisn',
        'nama',
        'jenis',
        'kelas_id',
        'aktif',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(TokenPemilih::class, 'pemilih_id');
    }

    public function suara(): HasMany
    {
        return $this->hasMany(Suara::class, 'pemilih_id');
    }

    public function kandidatAnggota(): HasMany
    {
        return $this->hasMany(KandidatAnggota::class, 'pemilih_id');
    }
}
