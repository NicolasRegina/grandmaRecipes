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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description', 500);
            $table->json('ingredients');
            $table->json('steps');
            $table->integer('prep_time');
            $table->integer('cook_time');
            $table->integer('servings');
            $table->enum('difficulty', ['Fácil', 'Media', 'Difícil']);
            $table->string('category');
            $table->json('tags')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->boolean('is_private')->default(false);
            $table->float('rating', 2, 1)->default(0);
            $table->integer('rating_count')->default(0);
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
