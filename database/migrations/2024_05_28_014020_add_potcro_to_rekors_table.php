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
            $table->decimal('POTCRO', 15, 2)->default(0)->after('PODTPO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekors', function (Blueprint $table) {
            $table ->dropColumn('POTCRO');
        });
    }
};
