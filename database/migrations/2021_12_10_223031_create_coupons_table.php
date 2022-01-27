<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('coupon_id'); // Coupon ID on Woo

            $table->string('code', 100);
            $table->integer('amount')->default(0);
            $table->dateTime('date_created')->nullable();
            $table->dateTime('date_modified')->nullable();
            $table->string('discount_type', 100)->default('fixed_cart');
            $table->text('description')->nullable(true);
            $table->dateTime('date_expires')->nullable(true);
            $table->integer('usage_count')->default(0);
            $table->boolean('individual_use')->default(0);
            $table->jsonb('settings')->nullable(false);
            $table->jsonb('meta_data')->nullable();

            $table->index(['code', 'coupon_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
