<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_options', function (Blueprint $table) {
            $table->increments('order_option_id')->comment('訂單的商品選項ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('order_id')->default(0)->comment('訂單ID');
            $table->integer('order_product_id')->default(0)->comment('訂單所屬的商品ID');
            $table->integer('product_option_id')->default(0)->comment('商品選項的ID');
            $table->integer('product_option_value_id')->default(0)->comment('商品選項值ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_options');
    }
}
