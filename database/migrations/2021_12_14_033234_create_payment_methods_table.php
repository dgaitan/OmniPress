<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('payment_method_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('enabled')->default(false);
            $table->string('method_title', 255);
            $table->text('method_description')->nullable();
            $table->jsonb('method_supports')->nullable();
            $table->jsonb('settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
