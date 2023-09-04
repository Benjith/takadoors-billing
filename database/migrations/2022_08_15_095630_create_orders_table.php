<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users");
            $table->string('code');
            $table->double('length', 10, 2);
            $table->double('width', 10, 2);
            $table->string('quantity');
            $table->string('design');
            $table->string('frame');
            $table->string('remarks');
            $table->string('thickness');
            $table->integer('status')->comment("1-produced,2-finished,3-dispatched,4-billing")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
