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
            $table->float('percent')->nullable()->comment('Процент участия в достижении');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_entries', function (Blueprint $table) {
            $table->dropColumn('percent');
        });
    }
};
