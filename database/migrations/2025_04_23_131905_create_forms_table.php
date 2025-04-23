<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Название формы');
            $table->text('description')->nullable()->comment('Описание формы');
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();
            $table->string('points')->nullable()->comment('Количество баллов');
            $table->foreignId('form_template_id')
                ->constrained('form_templates')
                ->cascadeOnDelete();
            $table->boolean('is_active')->default(true)->comment('Форма активна');
            $table->boolean('single_entry')->default(false)->comment('Ограничение: только одна запись на пользователя');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forms');
    }
};
