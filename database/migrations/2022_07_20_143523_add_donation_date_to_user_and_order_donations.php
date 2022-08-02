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
        Schema::table('user_donations', function (Blueprint $table) {
            $table->datetime('donation_date')->nullable()->default(null);
        });

        Schema::table('order_donations', function (Blueprint $table) {
            $table->datetime('donation_date')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_donations', function (Blueprint $table) {
            $table->dropColumn('donation_date');
        });

        Schema::table('order_donations', function (Blueprint $table) {
            $table->dropColumn('donation_date');
        });
    }
};
