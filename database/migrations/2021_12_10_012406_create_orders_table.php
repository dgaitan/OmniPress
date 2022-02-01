<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('order_id')->unique()->nullable(false);
            $table->bigInteger('parent_id')->default(0)->nullable();
            $table->bigInteger('number')->default(0)->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->string('order_key')->nullable();
            $table->string('created_via', 256)->default('checkout')->nullable();
            $table->string('version', 100)->nullable();
            $table->string('status', 100)->default('processing')->nullable();
            $table->string('currency', 4)->default('USD')->nullable();
            $table->dateTime('date_created')->nullable();
            $table->dateTime('date_modified')->nullable();
            $table->integer('discount_total')->default(0)->nullable();
            $table->integer('discount_tax')->default(0)->nullable();
            $table->integer('shipping_total')->default(0)->nullable();
            $table->integer('shipping_tax')->default(0)->nullable();
            $table->integer('cart_tax')->default(0)->nullable();
            $table->integer('total')->default(0)->nullable();
            $table->integer('total_tax')->default(0)->nullable();
            $table->boolean('prices_include_tax')->default(true)->nullable();
            $table->string('customer_ip_address', 50)->default('')->nullable(true);
            $table->string('customer_user_agent', 255)->default('')->nullable(true);
            $table->string('transaction_id', 255)->default('')->nullable(true);
            $table->dateTime('date_paid')->nullable(true);
            $table->dateTime('date_completed')->nullable(true);
            $table->string('cart_hash', 255)->nullable(true);
            $table->boolean('set_paid')->default(false)->nullable();

            // Structure dasta
            $table->jsonb('meta_data')->nullable(true);
            $table->jsonb('billing')->nullable();
            $table->jsonb('shipping')->nullable();
            $table->jsonb('tax_lines')->nullable();
            $table->jsonb('shipping_lines')->nullable();
            $table->jsonb('coupon_lines')->nullable();
            $table->jsonb('fee_lines')->nullable();

            // Memembership Things
            $table->foreignId('membership_id')->nullable();
            $table->boolean('has_membership')->default(false)->nullable();

            // add indexes
            $table->index(['order_id', 'number', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
