<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('membership_id');
            $table->text('description')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_logs');
    }
}
