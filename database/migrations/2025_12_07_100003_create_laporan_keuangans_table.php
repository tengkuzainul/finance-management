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
      Schema::create('laporan_keuangans', function (Blueprint $table) {
         $table->id();
         $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
         $table->foreignId('karyawan_id')->nullable()->constrained('karyawans')->onDelete('set null');
         $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');

         // Periode Laporan
         $table->date('tanggal');
         $table->enum('jenis', ['Pemasukan', 'Pengeluaran']);

         // Detail Transaksi
         $table->string('kategori');
         $table->string('keterangan');
         $table->decimal('jumlah', 15, 2);
         $table->string('bukti_transaksi')->nullable();
         $table->text('catatan')->nullable();

         // Status
         $table->enum('status', ['Draft', 'Pending', 'Approved', 'Rejected'])->default('Pending');
         $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
         $table->timestamp('approved_at')->nullable();

         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('laporan_keuangans');
   }
};
