<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            
            // KODE PENDAFTARAN UNIK - TANPA AFTER
            $table->string('kode_pendaftaran')->unique();
            
            // Data Kelompok
            $table->string('nama_kelompok');
            $table->integer('jumlah_anggota');
            $table->string('institusi');
            
            // Data Perwakilan
            $table->string('perwakilan_nama');
            $table->enum('perwakilan_jenis_kelamin', ['L', 'P']);
            $table->string('perwakilan_tempat_lahir');
            $table->date('perwakilan_tanggal_lahir');
            $table->string('perwakilan_email')->unique();
            $table->string('perwakilan_wa');
            $table->text('perwakilan_alamat');
            
            // Data Pendidikan Perwakilan
            $table->enum('perwakilan_jenis_pendidikan', ['smk', 'Kuliah']);
            
            // SMK Fields
            $table->string('perwakilan_sekolah')->nullable();
            $table->string('perwakilan_jurusan_smk')->nullable();
            $table->string('perwakilan_kelas')->nullable();
            $table->string('perwakilan_nis')->nullable();
            $table->string('perwakilan_guru_pembimbing')->nullable();
            $table->string('perwakilan_no_hp_guru')->nullable();
            
            // Kuliah Fields
            $table->string('perwakilan_kampus')->nullable();
            $table->string('perwakilan_jurusan_univ')->nullable();
            $table->string('perwakilan_semester')->nullable();
            $table->string('perwakilan_nim')->nullable();
            $table->string('perwakilan_dosen_pembimbing')->nullable();
            $table->string('perwakilan_no_hp_dosen')->nullable();
            $table->string('perwakilan_cv')->nullable();
            
            // Motivasi
            $table->text('alasan_pkl_gi');
            $table->text('skill_ingin_dipelajari');
            $table->text('harapan_setelah_pkl');
            
            // Periode
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // Dokumen
            $table->string('upload_surat_pengantar');
            
            // Catatan Admin
            $table->text('catatan_admin')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelompoks');
    }
};