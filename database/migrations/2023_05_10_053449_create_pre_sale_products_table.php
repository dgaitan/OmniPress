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
        Schema::create('pre_sale_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('product_name', 500);
            $table->bigInteger('woo_product_id');
            $table->date('release_date');
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pre_sale_products');
    }
};
