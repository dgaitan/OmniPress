<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWooSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woo_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('setting_id')->unique();
            $table->string('label', 255)->nullable();
            $table->text('description');
            $table->foreignId('parent_id')->nullable();
            $table->string('sub_groups')->nullable();

            $table->index(['setting_id', 'label']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('woo_settings');
    }
}
