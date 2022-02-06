<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsToCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_to_category', function (Blueprint $table) {
            $table->integer('news_id')->comment('最新分類ID');
            $table->integer('news_category_id')->comment('最新消息分類ID');

            //索引
            $table->unique(['news_id', 'news_category_id'], 'unique_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_to_category');
    }
}
