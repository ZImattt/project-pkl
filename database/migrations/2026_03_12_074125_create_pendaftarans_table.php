<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            
            // KODE PENDAFTARAN UNIK
            $table->string('kode_pendaftaran')->unique();
            
            // Data diri
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat_lengkap');
            $table->string('email')->unique();
            $table->string('no_whatsapp');
            
            // Pendidikan
            $table->enum('jenis_pendidikan', ['smk', 'kuliah']);
            
            // SMK
            $table->string('sekolah')->nullable();
            $table->string('jurusan_smk')->nullable();
            $table->string('kelas')->nullable();
            $table->string('nis')->nullable();
            $table->string('guru_pembimbing')->nullable();
            $table->string('no_hp_guru')->nullable();
            
            // Kuliah
            $table->string('kuliah')->nullable();
            $table->string('jurusan_kuliah')->nullable();
            $table->string('semester')->nullable();
            $table->string('nim')->nullable();
            $table->string('dosen_pembimbing')->nullable();
            $table->string('no_hp_dosen')->nullable();
            
            // Motivasi
            $table->text('alasan_pkl_gi');
            $table->text('skill_ingin_dipelajari');
            $table->text('harapan_setelah_pkl');
            
            // Periode
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // Dokumen
            $table->string('file_surat_pengantar')->nullable();
            $table->string('cv_ind')->nullable(); 
            
            // Bidang/Minat (opsional)
            $table->string('bidang')->nullable();
            
            // Catatan Admin
            $table->text('catatan_admin')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            
            $table->timestamps();
            
            // Index untuk performa
            $table->index('kode_pendaftaran');
            $table->index('status');
            $table->index('created_at');
            $table->index('jenis_pendidikan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftarans');
    }
};