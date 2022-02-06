<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionValueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_value_details', function (Blueprint $table) {
            $table->integer('product_option_value_id')->comment('商品選項值ID');
            $table->enum('language', ['zh-tw', 'en-us'])->comment('語系');
            $table->string('name')->default('')->comment('商品選項值名稱');

            //索引
            $table->unique(['product_option_value_id', 'language'], 'unique_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_option_value_details');
    }
}
