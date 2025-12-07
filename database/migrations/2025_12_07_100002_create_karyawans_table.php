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
      Schema::create('karyawans', function (Blueprint $table) {
         $table->id();
         $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
         $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

         // Data Personal
         $table->string('nik')->unique();
         $table->string('nama_lengkap');
         $table->string('tempat_lahir');
         $table->date('tanggal_lahir');
         $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
         $table->text('alamat');
         $table->string('no_telepon');
         $table->string('email')->unique();
         $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
         $table->enum('status_pernikahan', ['Belum Menikah', 'Menikah', 'Cerai']);

         // Data Pekerjaan
         $table->string('jabatan');
         $table->date('tanggal_masuk');
         $table->decimal('gaji_pokok', 15, 2)->default(0);
         $table->enum('status_karyawan', ['Tetap', 'Kontrak', 'Magang']);
         $table->boolean('is_active')->default(true);

         // Foto
         $table->string('foto')->nullable();

         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('karyawans');
   }
};
