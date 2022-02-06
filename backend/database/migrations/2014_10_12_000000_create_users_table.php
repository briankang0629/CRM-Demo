<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id')->comment('會員ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('user_group_id')->default(0)->comment('會員群組ID');
            $table->string('name')->nullable()->comment('會員名稱');
            $table->string('account')->unique('account')->nullable()->comment('帳號');
            $table->string('password')->nullable()->comment('密碼');
            $table->string('email')->unique('email')->nullable()->comment('email');
            $table->string('mobile')->index('mobile')->nullable()->comment('手機號碼');
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['Y','N'])->default('N')->comment('狀態');
            $table->string('picture')->nullable()->comment('圖片位置');
            $table->integer('upload_id')->nullable()->comment('上傳ID');
            $table->ipAddress('ip')->nullable()->comment('ip');
            $table->dateTime('last_login_time')->nullable()->comment('上次登入時間');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
