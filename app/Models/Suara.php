<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Suara extends Model
{
    use HasFactory;

    protected $table = 'suara';

    public const UPDATED_AT = null;

    protected $fillable = [
        'periode_id',
        'kandidat_id',
        'tipe_pemilih',
        'pemilih_id',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePemilihan::class, 'periode_id');
    }

    public function kandidat(): BelongsTo
    {
        return $this->belongsTo(Kandidat::class, 'kandidat_id');
    }
}
