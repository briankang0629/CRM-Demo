<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_details', function (Blueprint $table) {
            $table->integer('news_id')->comment('最新消息ID');
            $table->enum('language', ['zh-tw', 'en-us'])->comment('語系');
            $table->string('name')->default('')->comment('名稱');
            $table->string('intro')->default('')->comment('簡介');
            $table->text('description')->default('')->comment('內文描述');
            $table->string('meta_title')->default('')->comment('網頁標題');
            $table->string('meta_description')->default('')->comment('網頁描述');
            $table->string('meta_keyword')->default('')->comment('網頁關鍵字');

            //索引
            $table->unique(['news_id', 'language'], 'unique_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_details');
    }
}
