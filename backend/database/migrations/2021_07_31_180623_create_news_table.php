<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('news_id')->comment('最新消息ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('upload_id')->default(0)->index('upload_id')->comment('上傳多媒體ID');
            $table->smallInteger('sort_order')->default(0)->index('sort_order')->comment('排序');
            $table->enum('status', ['Y','N'])->default('N')->comment('狀態');
            $table->enum('top', ['Y','N'])->default('N')->comment('置頂');
            $table->integer('view')->default(0)->comment('觀看數');
            $table->dateTime('available_time')->nullable()->comment('有效時間');
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
        Schema::dropIfExists('news');
    }
}
