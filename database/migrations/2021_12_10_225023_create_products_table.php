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
            $table->string('name', 500);
            $table->string('slug', 500);
            $table->string('permalink', 500);
            $table->string('sku', 100);
            $table->dateTime('date_created');
            $table->dateTime('date_modified')->nullable(true);
            $table->string('type', 100)->default('simple');
            $table->string('status', 100)->default('publish');
            $table->boolean('featured')->default(false);
            $table->boolean('on_sale')->default(false);
            $table->boolean('purchasable')->default(false);
            $table->boolean('virtual')->default(false);
            $table->boolean('manage_stock')->default(false);
            $table->integer('stock_quantity')->default(0);
            $table->string('stock_status', 100)->default('instock');
            $table->boolean('sold_individually')->default(false);

            // Prices
            $table->integer('price')->default(0);
            $table->integer('regular_price')->default(0);
            $table->integer('sale_price')->default(0);

            
            $table->jsonb('settings');
            $table->jsonb('meta_data');

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
