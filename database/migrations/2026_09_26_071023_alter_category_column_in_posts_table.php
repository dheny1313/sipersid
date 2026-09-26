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
        Schema::table('posts', function (Blueprint $table) {
            // Mengubah tipe kolom menjadi string/VARCHAR dengan kapasitas 100 karakter
            $table->string('category', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // (Opsional) kembalikan ke kondisi semula jika di-rollback
            $table->string('category')->change();
        });
    }
};
