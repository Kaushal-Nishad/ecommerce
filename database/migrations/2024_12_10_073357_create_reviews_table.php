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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('product');
            $table->integer('user');
            $table->string('title');
            $table->text('desc')->nullable();
            $table->integer('rating')->default('0');
            $table->boolean('status')->default('1');
            $table->boolean('approved')->default('0');
            $table->boolean('hide_by_admin')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
