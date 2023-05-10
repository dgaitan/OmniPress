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
        Schema::create('order_product_relation', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('pre_order_id');
            $table->foreignId('pre_sale_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_product_relation');
    }
};
