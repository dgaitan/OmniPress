<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name', 255);
            $table->foreignId('creator_id');
            $table->foreignId('organization_id');
            $table->text('description')->nullable();
            $table->string('type', 255)->default('woocommerce');
            
            // Store credentials like a string encrypted.
            $table->text('access')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
