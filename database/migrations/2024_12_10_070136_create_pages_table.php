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
        Schema::create('pages', function (Blueprint $table) {
            $table->id('page_id');
            $table->string('page_title');
            $table->string('page_slug');
            $table->longText('description'); 
            $table->boolean('status')->default('1'); 
            $table->timestamps();
        });

        DB::table('pages')->insert([
            'page_title' => 'About',
            'page_slug' => 'about',
            'description' => 'Quisque imperdiet at ligula ut finibus. Duis facilisis mollis mi, in rutrum ipsum luctus eu. Aenean viverra leo et metus semper congue. Donec odio leo, porta sed sagittis eget, vestibulum quis lorem. Maecenas bibendum eleifend massa, sed viverra purus rhoncus a. Quisque posuere tortor sed nunc feugiat dapibus. Donec non arcu viverra, molestie velit nec, efficitur dolor. In ut semper sem, in fringilla mauris. Duis at consectetur mauris. Maecenas egestas libero lacus, nec sagittis turpis ullamcorper porttitor. Curabitur in lorem mi. Morbi nec nisi sit amet sem gravida volutpat a tempus neque. Nunc tincidunt quam tristique quam rutrum consectetur. Curabitur lobortis varius elit quis mattis. Aliquam tincidunt egestas velit nec bibendum.',
            'status' => '1'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
