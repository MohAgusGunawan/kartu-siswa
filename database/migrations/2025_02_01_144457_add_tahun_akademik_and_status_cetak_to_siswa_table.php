<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->foreignId('id_tahun_akademik')->nullable()->after('kelas_id')->constrained('tahun_akademik')->onDelete('set null');
            $table->enum('status_cetak', ['belum', 'sudah'])->default('belum')->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['id_tahun_akademik']);
            $table->dropColumn(['id_tahun_akademik', 'status_cetak']);
        });
    }
};

