<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('attribute_id')->nullable(false)->unique();
            $table->string('name', 255)->nullable(true);
            $table->integer('position')->default(0)->nullable(true);
            $table->boolean('visible')->default(false)->nullable();
            $table->boolean('variation')->default(false)->nullable();
            $table->jsonb('options')->nullable();

            $table->foreignId('product_id')->nullable();
            $table->index(['attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
