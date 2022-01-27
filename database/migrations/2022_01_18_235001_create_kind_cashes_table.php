<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKindCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kind_cashes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('membership_id')->nullable();
            $table->integer('points')->default(0);
            $table->integer('last_earned')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kind_cashes');
    }
}
