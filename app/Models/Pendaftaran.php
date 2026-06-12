<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftarans';
    
    protected $fillable = [
        'kode_pendaftaran',
        
        // Data Diri
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'email',
        'no_whatsapp',
        'alamat_lengkap',
        
        // Pendidikan
        'jenis_pendidikan',
        
        // SMK
        'sekolah',
        'jurusan_smk',
        'kelas',
        'nis',
        'guru_pembimbing',
        'no_hp_guru',
        
        // Perguruan Tinggi
        'kuliah',
        'jurusan_kuliah',
        'semester',
        'nim',
        'dosen_pembimbing',
        'no_hp_dosen',
        
        // Motivasi
        'alasan_pkl_gi',
        'skill_ingin_dipelajari',
        'harapan_setelah_pkl',
        
        // Periode
        'tanggal_mulai',
        'tanggal_selesai',
        
        // File & Status
        'file_surat_pengantar',
        'cv_ind',
        'status',
        'catatan_admin',
        'bidang'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}