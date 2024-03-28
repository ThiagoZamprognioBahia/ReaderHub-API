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
        Schema::create('books_readers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reader_id');
            $table->foreign('reader_id')
            ->references('id')
            ->on('readers')
            ->onDelete('cascade');
            $table->unsignedBigInteger('book_id');
            $table->foreign('book_id')
            ->references('id')
            ->on('books');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books_readers');
    }
};
