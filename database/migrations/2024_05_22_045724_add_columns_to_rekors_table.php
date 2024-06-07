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
            $table->string('POSTAT')->nullable();
            $table->string('PORECO')->nullable();
            $table->date('PODTVL')->nullable();
            $table->string('POREFN')->nullable();
            $table->date('PODTPO')->nullable();
            $table->string('POTCRO')->nullable();
            $table->string('PODESC')->nullable();
            $table->decimal('POAMNT', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekors', function (Blueprint $table) {
            $table->dropColumn(['POSTAT', 'PORECO', 'PODTVL', 'POREFN', 'PODTPO', 'POTRCO', 'PODESC', 'POAMNT']);
        });
    }
};
