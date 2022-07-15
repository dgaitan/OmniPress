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
        Schema::create('printforia_order_notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('order_id');
            $table->string('title', 500)->index();
            $table->text('body')->nullable();
            $table->string('order_status_code', 100)
                ->nullable()->default('unapproved');
            $table->datetime('note_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printforia_order_notes');
    }
};
