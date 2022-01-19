<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('customer_id')->default(0)->unique();
            $table->dateTime('date_created')->nullable(true);
            $table->dateTime('date_modified')->nullable(true);
            $table->string('email', 255)->index();
            $table->string('first_name', 255)->nullable(true);
            $table->string('last_name', 255)->nullable(true);
            $table->string('role', 255)->default('customer');
            $table->string('username', 255)->unique();
            $table->jsonb('billing');
            $table->jsonb('shipping');
            $table->boolean('is_paying_customer')->default(false);
            $table->string('avatar_url', 500)->nullable(true);
            $table->jsonb('meta_data')->nullable(true);

            // Add indexes
            $table->index(['customer_id', 'email', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
