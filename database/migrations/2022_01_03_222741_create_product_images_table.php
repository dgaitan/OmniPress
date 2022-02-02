<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('product_image_id')
                ->nullable(false)
                ->unique()
                ->constrained('products')
                ->onDelete('cascade');
            $table->dateTime('date_created')->nullable();
            $table->dateTime('date_modified')->nullable();
            $table->text('src')->nullable();
            $table->string('name')->nullable();
            $table->string('alt')->nullable();

            $table->foreignId('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
