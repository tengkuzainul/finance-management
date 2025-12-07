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
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn(['jabatan', 'status_karyawan', 'gaji_pokok']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->string('jabatan')->after('status_pernikahan');
            $table->decimal('gaji_pokok', 15, 2)->default(0)->after('tanggal_masuk');
            $table->enum('status_karyawan', ['Tetap', 'Kontrak', 'Magang'])->after('gaji_pokok');
        });
    }
};
