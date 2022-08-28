<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            //$table->foreign('user_id')->references('user_id')->on('users');
            $table->string('user_id');
            $table->string('status');
            $table->float('amount', 5, 2);
            //$table->foreign('payment_method')->references('payment_method')->on('payment_methods');
            $table->string('payment_method');
            //$table->foreign('product')->references('product')->on('products');
            $table->string('product');
            //$table->foreign('discount_id')->references('discount_id')->on('discounts');
            $table->string('discount_id');
            $table->string('description');
            $table->longText('other_payment_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
