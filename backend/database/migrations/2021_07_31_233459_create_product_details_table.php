<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->integer('product_id')->comment('商品ID');
            $table->enum('language', ['zh-tw', 'en-us'])->comment('語系');
            $table->string('name')->nullable()->comment('名稱');
            $table->string('intro')->nullable()->comment('簡介');
            $table->text('description')->nullable()->comment('內文描述');
            $table->string('meta_title')->nullable()->comment('網頁標題');
            $table->string('meta_description')->nullable()->comment('網頁描述');
            $table->string('meta_keyword')->nullable()->comment('網頁關鍵字');
            $table->string('tag')->nullable()->comment('標籤');

            //索引
            $table->unique(['product_id', 'language'], 'unique_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_details');
    }
}
