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
        Schema::create('printforia_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('order_id');
            $table->string('customer_reference', 500)->nullable();
            $table->text('ship_to_address')->nullable();
            $table->text('return_to_address')->nullable();
            $table->string('shipping_method', 255)->default('standard')->nullable();
            $table->string('ioss_number', 255)->nullable();
            $table->string('status', 255)->default('processing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printforia_orders');
    }
};
