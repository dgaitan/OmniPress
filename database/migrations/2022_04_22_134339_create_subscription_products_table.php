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
        Schema::create('subscription_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('product_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->datetime('expiration_date')->nullable();
            $table->integer('price')->nullable()->default(0);
            $table->integer('fee')->nullable()->default(0);
            $table->boolean('use_parent_settings')->default(true);

            $table->text('intervals')->nullable()->default('[]');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_subscription')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_products');
        Schema::dropColumns('products', ['has_subscription']);
    }
};
