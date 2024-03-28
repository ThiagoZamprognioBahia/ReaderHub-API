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
        Schema::table('readers', function (Blueprint $table) {
            $table->integer('total_books_read')->default(0)->after('birthday');
            $table->integer('total_pages_read')->default(0)->after('total_books_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('readers', function (Blueprint $table) {
            $table->dropColumn('total_books_read');
            $table->dropColumn('total_pages_read');
        });
    }
};
