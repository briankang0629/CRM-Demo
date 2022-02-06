<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id')->comment('商品ID');
            $table->integer('host_id')->comment('站別ID');
            $table->string('model')->index('model')->comment('商品型號');
            $table->decimal('cost_price', 15, 4)->default(0.0000)->comment('原價(成本價)');
            $table->decimal('price', 15, 4)->default(0.0000)->comment('銷售價格');
            $table->smallInteger('sort_order')->default(0)->index('sort_order')->comment('排序');
            $table->enum('status', ['Y','N'])->default('N')->comment('狀態');
            $table->integer('view')->default(0)->comment('觀看數');
            $table->integer('upload_id')->default(0)->comment('多媒體關聯ID');
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
        Schema::dropIfExists('products');
    }
}
