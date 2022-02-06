<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_records', function (Blueprint $table) {
            $table->increments('log_record_id')->comment('操作紀錄ID');
            $table->integer('log_id')->index('log_id')->comment('操作類別ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('admin_id')->index('admin_id')->comment('管理者ID');
            $table->integer('user_id')->index('user_id')->comment('用戶ID');
            $table->string('account')->index('account')->comment('帳號');
            $table->enum('class', ['A', 'U', 'S'])->index('class')->comment('使用者類型');
            $table->ipAddress('remote_ip')->index('remote_ip')->comment('ip');
            $table->ipAddress('server_addr')->comment('ServerIP');
            $table->text('server_info')->comment('檔頭資訊');
            $table->string('host')->comment('網址');
            $table->text('old_content')->comment('舊的紀錄');
            $table->text('new_content')->comment('新的紀錄');
            $table->text('path')->comment('檔案路徑');
            $table->date('new_date')->comment('新增日期');
            $table->timestamps();

            $table->index(['host_id', 'log_id']);
            $table->index(['host_id', 'new_date']);
            $table->index(['host_id', 'class']);
            $table->index(['host_id', 'account']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_records');
    }
}
