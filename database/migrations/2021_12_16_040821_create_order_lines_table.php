<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('order_line_id')->nullable()->unique();
            $table->string('name', 255)->nullable();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('variation_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('tax_class', 255)->nullable();
            $table->decimal('subtotal')->default(0)->nullable();
            $table->decimal('subtotal_tax')->default(0)->nullable();
            $table->decimal('total')->default(0)->nullable();
            $table->jsonb('taxes')->nullable();
            $table->jsonb('meta_data')->nullable();
            $table->string('sku', 200)->nullable();
            $table->decimal('price')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_lines');
    }
}
