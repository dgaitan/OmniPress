<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('product_subscription_id');
            $table->foreignId('variation_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->decimal('price', 9, 3)->default(0);
            $table->string('product_admin_slug', 255)->nullable();
            $table->text('image')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('fee', 9, 3)->default(0);
            $table->decimal('total', 9, 3)->default(0);
            $table->jsonb('interval_choices')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_items');
    }
}
