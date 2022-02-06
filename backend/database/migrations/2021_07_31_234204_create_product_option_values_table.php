<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_values', function (Blueprint $table) {
            $table->increments('product_option_value_id')->comment('商品選項值ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('product_option_id')->comment('商品選項ID');
            $table->decimal('price', 15, 4)->default(0.0000)->comment('額外加價');
            $table->smallInteger('sort_order')->default(0)->index('sort_order')->comment('排序');
            $table->integer('quantity')->default(0)->index('quantity')->comment('庫存');
            $table->decimal('point', 15, 4)->default(0.0000)->comment('增加點數');
            $table->decimal('weight', 15, 4)->default(0.0000)->comment('增加重量');
            $table->enum('is_stock', ['Y','N'])->default('N')->comment('是否增減庫存');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_option_values');
    }
}
