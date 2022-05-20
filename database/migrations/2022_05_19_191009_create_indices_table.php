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
        Schema::create('indices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('content_id')->index();
            $table->string('content_type');
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->text('meta')->nullable();
            $table->text('relations')->nullable();
            $table->bigInteger('views')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indices');
    }
};
