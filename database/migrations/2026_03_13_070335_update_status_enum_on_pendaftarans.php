<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumOnPendaftarans extends Migration
{
    public function up()
    {
        // Hapus dulu constraint yang ada
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status VARCHAR(20) DEFAULT 'pending'");
        
        // Truncate data yang nilainya ga sesuai (kalo perlu)
        DB::statement("UPDATE pendaftarans SET status = 'pending' WHERE status NOT IN ('pending', 'diterima', 'ditolak')");
        
        // Baru ubah ke ENUM dengan value lengkap
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status ENUM('pending', 'diterima', 'ditolak') DEFAULT 'pending'");
    }

    public function down()
    {
        // Ubah ke VARCHAR dulu biar aman
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status VARCHAR(20) DEFAULT 'pending'");
        
        // Update data yang 'diterima' jadi 'pending' karena di down ga ada 'diterima'
        DB::statement("UPDATE pendaftarans SET status = 'pending' WHERE status = 'diterima'");
        
        // Baru ubah ke ENUM yang lama
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status ENUM('pending', 'ditolak') DEFAULT 'pending'");
    }
}