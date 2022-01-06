<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('tax_rate_id')->unique(); // The tax id in woo

            $table->foreignId('service_id')->nullable();
            $table->string('country', 2)->default('US');
            $table->string('postcode', 100)->nullable(true);
            $table->string('city', 100)->nullable(true);
            $table->jsonb('postcodes')->nullable();
            $table->jsonb('cities')->nullable();
            $table->string('rate', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('compound')->default(false);
            $table->boolean('shipping')->default(false);
            $table->string('class', 255)->default('standard');

            $table->index(['tax_rate_id', 'postcode', 'class']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_rates');
    }
}
