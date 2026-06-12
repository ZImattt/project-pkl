<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

    protected $fillable = [
        'kode_pendaftaran',
        'nama_kelompok',
        'jumlah_anggota',
        'institusi',
        
        // Data Perwakilan
        'perwakilan_nama',
        'perwakilan_jenis_kelamin',
        'perwakilan_tempat_lahir',
        'perwakilan_tanggal_lahir',
        'perwakilan_email',
        'perwakilan_wa',
        'perwakilan_alamat',
        
        // Pendidikan
        'perwakilan_jenis_pendidikan',
        
        // SMK
        'perwakilan_sekolah',
        'perwakilan_jurusan_smk',
        'perwakilan_kelas',
        'perwakilan_nis',
        'perwakilan_guru_pembimbing',
        'perwakilan_no_hp_guru',
        
        // perguruan tinggi
        'perwakilan_kampus',
        'perwakilan_jurusan_univ',
        'perwakilan_semester',
        'perwakilan_nim',
        'perwakilan_dosen_pembimbing',
        'perwakilan_no_hp_dosen',
        'perwakilan_cv',
        
        // Motivasi
        'alasan_pkl_gi',
        'skill_ingin_dipelajari',
        'harapan_setelah_pkl',
        
        // Periode
        'tanggal_mulai',
        'tanggal_selesai',
        
        // File
        'upload_surat_pengantar',
        'status',
        'catatan_admin'
    ];

    public function anggota()
    {
        return $this->hasMany(Anggota::class);
    }
}