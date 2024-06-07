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
            $table->renameColumn('no_rek', 'tanggal');
            $table->renameColumn('nama_rek', 'referensi');
            $table->renameColumn('file_path', 'deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekors', function (Blueprint $table) {
            $table->renameColumn('tanggal', 'no_rek');
            $table->renameColumn('referensi', 'nama_rek');
            $table->renameColumn('deskripsi', 'file_path');
        });
    }
};
