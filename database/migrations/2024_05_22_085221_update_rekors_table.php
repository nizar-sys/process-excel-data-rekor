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
        Schema::table('rekors', function (Blueprint $table) {
            // Menghapus kolom yang tidak diperlukan lagi
            $table->dropColumn(['tanggal', 'referensi', 'deskripsi', 'debit', 'kredit', 'saldo']);
            
           
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
