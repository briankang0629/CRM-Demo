<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->increments('product_option_id')->comment('商品選項ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('product_id')->comment('商品ID');
            $table->enum('type', ['radio', 'checkbox', 'select'])->default('radio')->comment('選項類型');
            $table->smallInteger('sort_order')->default(0)->index('sort_order')->comment('排序');
            $table->enum('required', ['Y','N'])->default('N')->comment('是否必填');
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
        Schema::dropIfExists('product_options');
    }
}
