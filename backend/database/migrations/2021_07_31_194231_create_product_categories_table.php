<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->increments('product_category_id')->comment('商品分類ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('parent_id')->index('parent_id')->comment('上層ID');
            $table->smallInteger('sort_order')->default(0)->index('sort_order')->comment('排序');
            $table->enum('status', ['Y','N'])->default('N')->comment('狀態');
            $table->text('family')->nullable()->comment('家族');
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
        Schema::dropIfExists('product_categories');
    }
}
