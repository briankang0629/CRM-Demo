<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category_paths', function (Blueprint $table) {
            $table->integer('product_category_id')->unsigned()->comment('分類ID');
            $table->integer('path_id')->unsigned()->comment('對應上層ID');
            $table->integer('level')->unsigned()->comment('等級');

            $table->unique(['product_category_id', 'path_id'], 'category_tree');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_category_paths');
    }
}
