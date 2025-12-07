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
        Schema::create('gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->date('tanggal'); // Tanggal gaji (per hari)
            $table->decimal('total_pemasukan', 15, 2)->default(0); // Total pemasukan hari itu
            $table->decimal('persen_gaji', 5, 2)->default(13); // Persentase gaji saat itu
            $table->decimal('nominal_gaji', 15, 2)->default(0); // Hasil perhitungan gaji
            $table->integer('jumlah_transaksi')->default(0); // Jumlah transaksi yang di-approve
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Unique constraint: 1 karyawan hanya bisa punya 1 gaji per tanggal
            $table->unique(['karyawan_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajis');
    }
};
