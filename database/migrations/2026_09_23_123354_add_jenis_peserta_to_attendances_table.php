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
        Schema::table('attendances', function (Blueprint $table) {
            // Tambahkan kolom jenis_peserta setelah member_name
            // Kita set default 'DPR' agar data lama tidak error
            $table->string('jenis_peserta', 50)->default('DPR')->after('member_name');

            // Ubah kolom fraksi menjadi nullable (boleh kosong untuk tamu umum)
            $table->string('fraksi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('jenis_peserta');
            $table->string('fraksi')->nullable(false)->change();
            //
        });
    }
};
