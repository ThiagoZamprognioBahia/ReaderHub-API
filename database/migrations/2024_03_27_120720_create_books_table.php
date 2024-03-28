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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('genre_id');
            $table->foreign('genre_id')
                ->on('genres')
                ->references('id');
            $table->string('author');
            $table->year('year');
            $table->integer('pages');
            $table->string('language');
            $table->string('edition');
            $table->foreignId('publisher_id')->nullable();
            $table->foreign('publisher_id')
                ->on('publishers')
                ->references('id');
            $table->string('isbn')->nullable();
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
