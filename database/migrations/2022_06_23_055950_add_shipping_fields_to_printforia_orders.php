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
        Schema::table('printforia_orders', function (Blueprint $table) {
            $table->string('carrier', 200)->nullable();
            $table->string('tracking_number', 500)->nullable();
            $table->text('tracking_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('printforia_orders', function (Blueprint $table) {
            $table->dropColumn(['carrier', 'tracking_number', 'tracking_url']);
        });
    }
};
