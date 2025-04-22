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
        Schema::create('field_entry_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_entry_id')
                ->constrained('form_entries')
                ->cascadeOnDelete();
            $table->foreignId('template_field_id')
                ->constrained('template_fields')
                ->cascadeOnDelete();
            $table->text('value')->nullable()->comment('Значение, приведённое к строке');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_entry_values');
    }
};
