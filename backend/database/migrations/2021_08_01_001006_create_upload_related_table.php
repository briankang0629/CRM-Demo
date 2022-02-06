<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_related', function (Blueprint $table) {
            $table->integer('upload_id')->index('upload_id')->comment('上傳ID');
            $table->enum('category', ['product', 'news', 'article'])->default('product')->index('category')->comment('分類');
            $table->integer('id')->default(0)->index('id')->comment('關聯ID');
            $table->smallInteger('sort_order')->default(0)->index('sort_order')->comment('排序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_related');
    }
}
