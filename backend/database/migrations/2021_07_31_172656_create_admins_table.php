<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('admin_id')->comment('管理者ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('parent_id')->default(0)->comment('上級ID');
            $table->string('name')->index('name')->nullable()->comment('管理者名稱');
            $table->string('account')->unique('account')->nullable()->comment('帳號');
            $table->string('password')->nullable()->comment('密碼');
            $table->string('email')->unique('email')->nullable()->comment('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['Y','N'])->default('N')->comment('狀態');
            $table->string('picture')->nullable()->comment('圖片');
            $table->tinyInteger('level')->nullable()->comment('等級');
            $table->text('family')->nullable()->comment('家族');
            $table->enum('is_sub', ['Y', 'N'])->default('N')->comment('子帳號');
            $table->ipAddress('ip')->nullable()->comment('ip');
            $table->integer('permission_id')->nullable()->comment('權限ID');
            $table->integer('upload_id')->nullable()->comment('上傳ID');
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
        Schema::dropIfExists('admins');
    }
}
