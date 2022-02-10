<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('customer_id');
            $table->string('customer_email')->index();
            $table->foreignId('product_id')->nullable();
            $table->date('start_at');
            $table->date('end_at');
            $table->integer('price')->default(0);
            $table->string('shipping_status', 100)->default('pending');
            $table->string('status', 100)->default('active');
            $table->bigInteger('pending_order_id')->nullable();
            $table->date('last_payment_intent')->nullable();
            $table->integer('payment_intents')->default(0);
            $table->foreignId('kind_cash_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberships');
    }
}
