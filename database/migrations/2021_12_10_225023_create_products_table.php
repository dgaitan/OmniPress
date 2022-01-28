<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('product_id')->unique(); // Product ID on Woo

            $table->foreignId('parent_id')->nullable(true);
            $table->string('name', 500)->nullable();
            $table->string('slug', 500)->nullable();
            $table->string('permalink', 500)->nullable();
            $table->string('sku', 100)->nullable();
            $table->dateTime('date_created')->nullable();
            $table->dateTime('date_modified')->nullable(true);
            $table->string('type', 100)->default('simple')->nullable();
            $table->string('status', 100)->default('publish')->nullable();
            $table->boolean('featured')->default(false)->nullable();
            $table->boolean('on_sale')->default(false)->nullable();
            $table->boolean('purchasable')->default(false)->nullable();
            $table->boolean('virtual')->default(false)->nullable();
            $table->boolean('manage_stock')->default(false)->nullable();
            $table->integer('stock_quantity')->default(0)->nullable();
            $table->string('stock_status', 100)->default('instock')->nullable();
            $table->boolean('sold_individually')->default(false)->nullable();

            // Prices
            $table->integer('price')->default(0)->nullable();
            $table->integer('regular_price')->default(0)->nullable();
            $table->integer('sale_price')->default(0)->nullable();

            
            $table->jsonb('settings')->nullable();
            $table->jsonb('meta_data')->nullable();

            $table->index(['slug', 'sku', 'status', 'stock_status', 'product_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
