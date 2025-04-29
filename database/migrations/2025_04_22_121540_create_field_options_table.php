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
        Schema::create('field_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_field_id')
                ->constrained('template_fields')
                ->cascadeOnDelete();
            $table->string('value')->comment('Хранимое значение');
            $table->string('label')->comment('Отображаемый текст');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_options');
    }
};
