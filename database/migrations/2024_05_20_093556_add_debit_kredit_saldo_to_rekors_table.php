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
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('kredit', 15, 2)->default(0.00);
            $table->decimal('saldo', 15, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekors', function (Blueprint $table) {
            $table->dropColumn(['debit', 'kredit', 'saldo']);
        });
    }
};
