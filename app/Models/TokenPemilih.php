<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TokenPemilih extends Model
{
    use HasFactory;

    protected $table = 'token_pemilih';

    protected $fillable = [
        'periode_id',
        'tipe_pemilih',
        'pemilih_id',
        'token_hash',
        'digunakan_pada',
        'kadaluarsa_pada',
    ];

    protected $hidden = [
        'token_hash',
    ];

    protected function casts(): array
    {
        return [
            'digunakan_pada' => 'datetime',
            'kadaluarsa_pada' => 'datetime',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePemilihan::class, 'periode_id');
    }
}
