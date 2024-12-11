<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('pagelink');
            $table->boolean('status')->default(1);
            $table->string('banner_img');
            $table->timestamps();
        });

        DB::table('banner')->insert([
            ['title' => 'Banner First',
            'pagelink' => 'https://www.easystepstechnologies.com',
            'status' => '1',
            'banner_img' => 'b1.jpg'],
            ['title' => 'Banner Second',
            'pagelink' => 'https://www.easystepstechnologies.com',
            'status' => '1',
            'banner_img' => 'b2.jpg'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
