<?php

use App\Models\WooCommerce\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignIdFor(Order::class)->constrained()->onDelete('cascade');
            $table->string('author', 255)->default('system')->nullable();
            $table->dateTime('date_created');
            $table->text('note')->nullable();
            $table->boolean('customer_note')->default(false);
            $table->boolean('added_by_user')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_notes');
    }
}
