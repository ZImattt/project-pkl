<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nim_nis');
            $table->string('email');
            $table->string('telepon');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('is_perwakilan')->default(false);
            $table->timestamps();
            
            $table->unique(['kelompok_id', 'email']);
            $table->unique(['kelompok_id', 'nim_nis']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('anggotas');
    }
};