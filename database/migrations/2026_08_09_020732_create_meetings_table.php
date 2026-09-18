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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Contoh: Rapat Paripurna Ke-5 Pembahasan APBD
            $table->string('slug')->unique(); // Untuk URL detail sidang di landing page
            $table->dateTime('meeting_date'); // Tanggal & Jam Sidang
            $table->string('location'); // Lokasi / Ruangan Sidang
            $table->text('description')->nullable(); // Agenda / Pembahasan
            $table->text('result_summary')->nullable(); // Hasil / Kesimpulan Sidang (Diisi setelah selesai)
            $table->enum('status', ['Scheduled', 'On Going', 'Completed', 'Cancelled'])->default('Scheduled');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Admin pembuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
