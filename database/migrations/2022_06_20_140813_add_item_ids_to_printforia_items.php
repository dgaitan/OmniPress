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
            $table->string('printforia_order_id')->index();
        });

        Schema::table('printforia_order_items', function (Blueprint $table) {
            $table->string('printforia_item_id')->index();
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
            $table->dropColumn('printforia_order_id');
        });

        Schema::table('printforia_order_items', function (Blueprint $table) {
            $table->dropColumn('printforia_item_id');
        });
    }
};
