<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Проверяем, есть ли столбец user_id
        if (Schema::hasColumn('authors', 'user_id')) {
            // Удаляем возможные дубликаты связей
            Schema::table('authors', function (Blueprint $table) {
                // Пытаемся удалить старый внешний ключ, если он есть
                $table->dropForeign(['user_id']);
            });

            // Добавляем правильный внешний ключ
            Schema::table('authors', function (Blueprint $table) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        } else {
            // Если столбца нет (маловероятно в вашем случае)
            Schema::table('authors', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->after('id');
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }

        // Обновляем данные, если нужно
        // Например, установить user_id = 1 для всех записей, где он NULL
        DB::table('authors')->whereNull('user_id')->update(['user_id' => 1]);
    }

    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            // Не удаляем столбец, так как он нужен для работы приложения
        });
    }
};
