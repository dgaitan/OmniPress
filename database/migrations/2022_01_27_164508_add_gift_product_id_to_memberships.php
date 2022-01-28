<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGiftProductIdToMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_product', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('product_id');
            $table->foreignId('membership_id');
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->boolean('user_picked_gift')->nullable()->default(false);
            $table->integer('gift_product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            Schema::dropIfExists('membership_product');
        });
    }
}
