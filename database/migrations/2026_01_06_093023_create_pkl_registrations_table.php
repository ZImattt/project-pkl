<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pkl_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_id')->nullable()->unique();
            
            // ================= DATA PRIBADI =================
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat_lengkap');
            $table->string('email');
            $table->string('no_whatsapp');
            
            // ================= JENIS PENDIDIKAN =================
            $table->enum('jenis_pendidikan', ['smk', 'universitas']);
            
            // ================= DATA SMK =================
            $table->string('nis')->nullable();
            $table->string('kelas')->nullable();
            $table->string('sekolah')->nullable();
            $table->string('jurusan_smk')->nullable();
            $table->string('guru_pembimbing')->nullable();
            $table->string('no_hp_guru')->nullable();
            
            // ================= DATA UNIVERSITAS =================
            $table->string('nim')->nullable();
            $table->string('semester')->nullable();
            $table->string('universitas')->nullable();
            $table->string('jurusan_univ')->nullable();
            $table->string('dosen_pembimbing')->nullable();
            $table->string('no_hp_dosen')->nullable();
            
            // ================= MOTIVASI & ALASAN =================
            $table->text('alasan_pkl_gi');
            $table->text('skill_ingin_dipelajari');
            $table->text('harapan_setelah_pkl');
            $table->text('pengalaman_sebelumnya')->nullable();
            
            // ================= PERIODE PKL =================
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // ================= DOKUMEN =================
            $table->string('upload_surat_pengantar');
            
            // ================= STATUS & ADMIN =================
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            
            $table->timestamps();
            
            // ================= INDEXES =================
            $table->index('no_whatsapp');
            $table->index('email');
            $table->index('registration_id');
            $table->index('status');
            $table->index('jenis_pendidikan');
            $table->index('sekolah');
            $table->index('universitas');
            $table->index('created_at');
            $table->index(['jenis_pendidikan', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkl_registrations');
    }
};