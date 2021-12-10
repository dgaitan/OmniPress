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
            $table->bigInteger('parent_id')->default(0);
            $table->bigInteger('number')->default(0);
            $table->string('order_key');
            $table->string('created_via', 256)->default('checkout');
            $table->string('version', 100);
            $table->string('status', 100)->default('processing');
            $table->string('currency', 4)->default('USD');
            $table->dateTime('date_created');
            $table->dateTime('date_modified');
            $table->decimal('discount_total', 9, 3)->default(0);
            $table->decimal('discount_tax', 9, 3)->default(0);
            $table->decimal('shipping_total', 9, 3)->default(0);
            $table->decimal('shipping_tax', 9, 3)->default(0);
            $table->decimal('cart_tax', 9, 3)->default(0);
            $table->decimal('total', 9, 3)->default(0);
            $table->decimal('total_tax', 9, 3)->default(0);
            $table->boolean('prices_include_tax')->default(true);
            $table->string('customer_ip_address', 50)->default('')->nullable(true);
            $table->string('customer_user_agent', 255)->default('')->nullable(true);
            $table->string('transaction_id', 255)->default('')->nullable(true);
            $table->dateTime('date_paid')->nullable(true);
            $table->dateTime('date_completed')->nullable(true);
            $table->string('cart_hash', 255)->nullable(true);
            $table->boolean('set_paid')->default(false);

            // Structure dasta
            $table->jsonb('meta_data')->nullable(true);
            $table->jsonb('billing');
            $table->jsonb('shipping');
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
