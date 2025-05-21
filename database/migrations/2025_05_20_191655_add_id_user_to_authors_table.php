<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdUserToAuthorsTable extends Migration
{
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            // Проверяем существование колонки перед добавлением
            if (!Schema::hasColumn('authors', 'id_user')) {
                $table->string('id_user')->nullable()->after('author_id');
            }
        });
    }

    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            // Откат - удаляем колонку
            if (Schema::hasColumn('authors', 'id_user')) {
                $table->dropColumn('id_user');
            }
        });
    }
}
