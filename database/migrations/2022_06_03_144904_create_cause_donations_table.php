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
        Schema::create('cause_donations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('cause_id');
            $table->date('from');
            $table->date('to');
            $table->bigInteger('amount')->default(0);
            $table->integer('total_orders')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cause_donations');
    }
};
