<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PklRegistration extends Model
{
    use HasFactory;

    protected $table = 'pkl_registrations';

    protected $fillable = [
        'registration_id',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'email',
        'no_whatsapp',
        'jenis_pendidikan',
        'nis',
        'kelas',
        'sekolah',
        'jurusan_smk',
        'guru_pembimbing',
        'no_hp_guru',
        'nim',
        'semester',
        'universitas',
        'jurusan_univ',
        'dosen_pembimbing',
        'no_hp_dosen',
        'alasan_pkl_gi',
        'skill_ingin_dipelajari',
        'harapan_setelah_pkl',
        'pengalaman_sebelumnya',
        'tanggal_mulai',
        'tanggal_selesai',
        'upload_surat_pengantar',
        'status',
        'admin_notes',
        'status_updated_at'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status_updated_at' => 'datetime',
    ];
}