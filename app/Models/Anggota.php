<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = [
        'kelompok_id',
        'nama',
        'jenis_kelamin',
        'nim_nis',
        'email',
        'telepon',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'is_perwakilan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_perwakilan' => 'boolean'
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}