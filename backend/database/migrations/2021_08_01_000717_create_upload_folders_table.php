<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_folders', function (Blueprint $table) {
            $table->increments('upload_folder_id')->comment('資料夾ID');
            $table->integer('host_id')->comment('站別ID');
            $table->enum('category', ['image', 'word', 'excel', 'video', 'txt'])->comment('資料夾分類');
            $table->string('code')->index('code')->comment('資料夾代碼');
            $table->string('name')->comment('自定義名稱');
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
        Schema::dropIfExists('upload_folders');
    }
}
