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
      Schema::table('users', function (Blueprint $table) {
         $table->text('ttd')->nullable()->after('avatar')->comment('Base64 encoded signature image');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::table('users', function (Blueprint $table) {
         $table->dropColumn('ttd');
      });
   }
};
