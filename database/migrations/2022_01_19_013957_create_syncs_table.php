<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syncs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name', 255);
            $table->string('content_type', 255)->default('orders');
            $table->text('description')->nullable();
            $table->foreignId('user_id');
            $table->string('status', 100)->default('completed');
            $table->integer('intents')->default(0)->nullable();
            $table->string('batch_id', 500)->nullable()->default('');
            $table->integer('current_page')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syncs');
    }
}
