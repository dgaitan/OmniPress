<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKindCashLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kind_cash_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('kind_cash_id');
            $table->text('event')->nullable();
            $table->dateTime('date')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->integer('points')->nullable()->default(0);
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kind_cash_logs');
    }
}
