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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
             $table->string('passenger_name'); // Nama Penumpang (Anggota / Staf)
            $table->string('sppd_number')->nullable(); // Nomor Surat Tugas / SPPD
            $table->string('destination');    // Kota Tujuan (Contoh: Jakarta / Surabaya)
            $table->string('airline');        // Maskapai (Contoh: Garuda Indonesia)
            $table->string('flight_number');  // Kode Penerbangan (Contoh: GA-502)
            $table->dateTime('departure_time'); // Waktu Keberangkatan
            $table->dateTime('return_time')->nullable(); // Waktu Kepulangan
            $table->string('ticket_code')->nullable(); // Kode Booking E-Ticket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
