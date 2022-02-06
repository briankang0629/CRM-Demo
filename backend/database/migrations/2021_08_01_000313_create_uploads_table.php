<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('upload_id')->comment('上傳檔案ID');
            $table->integer('host_id')->comment('站別ID');
            $table->string('file_name')->nullable()->comment('檔案名稱');
            $table->string('origin_name')->nullable()->comment('原始檔名');
            $table->enum('type', ['image', 'video'])->default('image')->comment('檔案類型EX:image,video');
            $table->string('extension')->nullable()->comment('副檔名');
            $table->string('size')->nullable()->comment('檔案大小');
            $table->smallInteger('height')->nullable()->comment('高度');
            $table->smallInteger('width')->nullable()->comment('寬度');
            $table->string('folder')->default('default')->comment('檔案位置');
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
        Schema::dropIfExists('uploads');
    }
}
