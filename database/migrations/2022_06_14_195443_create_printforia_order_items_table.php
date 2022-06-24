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
        Schema::create('printforia_order_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('order_id');
            $table->foreignId('product_id');
            $table->string('customer_item_reference', 500)->nullable();
            $table->string('printforia_sku', 500)->nullable();
            $table->string('kindhumans_sku', 500)->nullable();
            $table->integer('quantity')->default(1);
            $table->text('description')->nullable();
            $table->text('prints')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printforia_order_items');
    }
};
