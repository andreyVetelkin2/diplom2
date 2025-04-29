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
        Schema::create('template_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_template_id')
                ->constrained('form_templates')
                ->cascadeOnDelete();
            $table->string('name')->comment('Уникальный ключ поля, например “start_date”');
            $table->string('label')->comment('Человекочитабельное название');
            $table->enum('type', ['datetime', 'string', 'checkbox', 'list'])->comment('Тип поля');
            $table->boolean('required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_fields');
    }
};
