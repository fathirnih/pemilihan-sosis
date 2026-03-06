<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class KandidatAnggota extends Model
{
    use HasFactory;

    protected $table = 'kandidat_anggota';

    protected $fillable = [
        'kandidat_id',
        'pemilih_id',
        'peran',
    ];

    public function kandidat(): BelongsTo
    {
        return $this->belongsTo(Kandidat::class, 'kandidat_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Pemilih::class, 'pemilih_id');
    }
}
