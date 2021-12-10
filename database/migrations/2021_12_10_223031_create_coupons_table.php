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

            $table->string('code', 100);
            $table->decimal('amount', 9, 3)->default(0);
            $table->dateTime('date_created');
            $table->dateTime('date_modified');
            $table->string('discount_type', 100)->default('fixed_cart');
            $table->text('description')->nullable(true);
            $table->dateTime('date_expires')->nullable(true);
            $table->integer('usage_count')->default(0);
            $table->boolean('individual_use')->default(0);
            $table->jsonb('settings')->nullable(false);
            $table->jsonb('meta_data')->nullable();

            $table->index(['code']);
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
