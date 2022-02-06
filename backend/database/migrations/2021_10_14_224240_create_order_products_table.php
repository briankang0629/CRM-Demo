<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('order_product_id')->comment('訂單商品ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('order_id')->default(0)->comment('訂單ID');
            $table->integer('product_id')->default(0)->comment('商品ID');
            $table->integer('quantity')->default(0)->comment('數量');
            $table->decimal('price', 15, 4)->default(0)->comment('金額');
            $table->decimal('total', 15, 4)->default(0)->comment('小計');
            $table->decimal('tax', 15, 4)->default(0)->comment('稅金');
            $table->decimal('reward', 15, 4)->default(0)->comment('回饋金');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
