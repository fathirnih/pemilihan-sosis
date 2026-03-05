<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    use HasFactory;

    protected $table = 'kandidat';

    protected $fillable = [
        'periode_id',
        'nomor_urut',
        'visi',
        'misi',
        'foto',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePemilihan::class, 'periode_id');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(KandidatAnggota::class, 'kandidat_id');
    }

    public function suara(): HasMany
    {
        return $this->hasMany(Suara::class, 'kandidat_id');
    }
}
