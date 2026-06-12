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
         // Mengubah enum status_pernikahan untuk menambahkan Duda dan Janda
         $table->enum('status_pernikahan', ['Belum Menikah', 'Menikah', 'Cerai', 'Duda', 'Janda'])->change();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::table('karyawans', function (Blueprint $table) {
         // Kembalikan ke enum lama
         $table->enum('status_pernikahan', ['Belum Menikah', 'Menikah', 'Cerai'])->change();
      });
   }
};
