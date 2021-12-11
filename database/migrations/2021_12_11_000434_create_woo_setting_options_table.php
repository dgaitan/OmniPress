<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWooSettingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woo_setting_options', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('setting_option_id')->unique();
            $table->string('label', 255);
            $table->text('dscription')->nullable();
            $table->jsonb('value');
            $table->jsonb('default');
            $table->text('tip');
            $table->text('placeholder');
            $table->string('type', 255)->defeault('text');
            $table->jsonb('options');
            $table->bigInteger('group_id');

            $table->index(['setting_option_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('woo_setting_options');
    }
}
