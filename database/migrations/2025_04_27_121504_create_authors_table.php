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
       Schema::create('authors', function (Blueprint $table) {
           $table->id();
           $table->string('author_id')->nullable(); // ID автора в Google Scholar
           $table->string('name')->nullable() ;
           $table->text('affiliation')->nullable();
           $table->string('email')->nullable();
           $table->text('interests')->nullable();
           $table->integer('cited_by')->default(0);
           $table->string('google_key')->nullable();
           $table->timestamps();
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }

};
