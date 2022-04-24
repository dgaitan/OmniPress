<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kindhuman_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('customer_id');
            $table->foreignId('active_order_id')->nullable();
            
            $table->string('uuid', 500)->nullable();
            $table->string('status', 100)->default('active');
            $table->string('customer_email', 500)->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->integer('total')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_payment_date')->nullable();
            $table->date('last_payment')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_method')->nullable();
            $table->integer('payment_intents')->nullable()->default(0);
            $table->string('payment_interval', 255)->nullable();
            $table->text('cause')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('kindhuman_subscription_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kindhuman_subscriptions');
        Schema::dropColumns('orders', ['kindhuman_subscription_id']);
    }
};
