<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class PeriodePemilihan extends Model
{
    use HasFactory;

    protected $table = 'periode_pemilihan';

    protected $fillable = [
        'nama_periode',
        'mulai_pada',
        'selesai_pada',
        'status',
        'mode_pasangan',
    ];

    protected function casts(): array
    {
        return [
            'mulai_pada' => 'datetime',
            'selesai_pada' => 'datetime',
        ];
    }

    public function kandidat(): HasMany
    {
        return $this->hasMany(Kandidat::class, 'periode_id');
    }

    public function suara(): HasMany
    {
        return $this->hasMany(Suara::class, 'periode_id');
    }
}
