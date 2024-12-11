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
        Schema::create('paymentmethod', function (Blueprint $table) {
            $table->id();
            $table->string('payment_name');
            $table->string('payment_img');
            $table->boolean('payment_status')->default('1');
            $table->timestamps();
        });

        DB::table('paymentmethod')->insert([
            ['payment_name' => 'Razorpay',
            'payment_img' => 'razorpay.png',
            'payment_status' => '1',],
            ['payment_name' => 'Paypal',
            'payment_img' => 'paypal.png',
            'payment_status' => '1',],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymentmethod');
    }
};
