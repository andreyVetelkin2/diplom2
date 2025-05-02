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
        Schema::table('form_entries', function (Blueprint $table) {
            $table->foreignId('form_id')
                ->constrained('forms')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_entries', function (Blueprint $table) {
            $table->dropForeign(['form_id']); // Удаляем внешний ключ
            $table->dropColumn('form_id');    // Удаляем колонку
        });
    }
};
