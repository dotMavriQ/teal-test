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
            $table->string('title');
            $table->string('author');
            $table->text('description')->nullable();
            $table->string('isbn', 20)->nullable()->index();
            $table->string('isbn13', 20)->nullable()->index();
            $table->string('asin', 20)->nullable();
            $table->integer('num_pages')->nullable();
            $table->string('cover_image')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('format', 50)->nullable();
            $table->integer('user_rating')->nullable();
            $table->integer('avg_rating')->nullable();
            $table->date('date_added')->nullable();
            $table->date('date_started')->nullable();
            $table->date('date_read')->nullable();
            $table->boolean('owned')->default(false);
            $table->string('language', 50)->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
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