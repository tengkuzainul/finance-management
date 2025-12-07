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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Penerima notifikasi
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('set null'); // Pengirim/trigger
            $table->string('type'); // laporan_baru, laporan_approved, laporan_rejected
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable(); // URL untuk detail
            $table->nullableMorphs('notifiable'); // Polymorphic relation ke model terkait
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
