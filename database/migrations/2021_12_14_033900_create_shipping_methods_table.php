<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('shipping_method_id')->unique();
            $table->string('title', 255);
            $table->integer('order')->default(0);
            $table->boolean('enabled')->default(false);
            $table->integer('method_id')->nullable();
            $table->string('method_title', 255)->nullable();
            $table->text('method_description')->nullable();
            $table->jsonb('settings')->nullable();

            $table->index(['shipping_method_id', 'title', 'method_title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_methods');
    }
}
