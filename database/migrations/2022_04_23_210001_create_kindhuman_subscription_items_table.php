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
        Schema::create('kindhuman_subscription_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('subscription_id')->onDelete('cascade');
            $table->foreignId('product_id');
            
            $table->integer('regular_price')->nullable()->default(0);
            $table->integer('quantity')->default(1);
            $table->integer('price')->default(0);
            $table->integer('fee')->default(0);
            $table->integer('total')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kindhuman_subscription_items');
    }
};
