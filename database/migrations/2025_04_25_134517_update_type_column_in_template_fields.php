<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Выполнить миграцию.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('template_fields', function (Blueprint $table) {
            // Используем метод change для изменения столбца
            $table->enum('type', ['datetime', 'string', 'checkbox', 'list', 'file'])->change();
        });
    }

    /**
     * Откатить миграцию.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('template_fields', function (Blueprint $table) {
            // Откат изменений - удаляем 'file' из enum
            $table->enum('type', ['datetime', 'string', 'checkbox', 'list'])
                ->change();
        });
    }
};
