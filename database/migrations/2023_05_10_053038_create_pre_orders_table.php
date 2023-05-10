<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('woo_order_id');
            $table->string('customer_email', 100);
            $table->bigInteger('customer_id');
            $table->string('status', 100)->default('active');
            $table->date('release_date');
            $table->bigInteger('product_id');
            $table->string('product_name');
            $table->integer('sub_total');
            $table->integer('taxes');
            $table->integer('shipping');
            $table->integer('total');
            $table->boolean('released')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pre_orders');
    }
};
