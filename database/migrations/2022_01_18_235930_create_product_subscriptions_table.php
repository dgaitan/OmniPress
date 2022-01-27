<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('status', 100)->default('active');
            $table->string('uuid', 255);
            $table->foreignId('customer_id');
            $table->string('customer_email', 255);
            $table->integer('total')->default(0);
            $table->foreignId('payment_method_id')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_payment')->nullable();
            $table->date('last_payment')->nullable();
            $table->date('last_intent_date')->nullable();

            $table->jsonb('billing')->nullable();
            $table->jsonb('shipping')->nullable();

            $table->string('payment_interval', 255)->default('');
            $table->string('cause', 255)->nullable();
            $table->string('shipping_method')->nullable();
            $table->integer('payment_intents')->default(0)->nullable();
            $table->bigInteger('active_order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_subscriptions');
    }
}
