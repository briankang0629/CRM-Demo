<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->increments('system_setting_id')->comment('網站設定ID');
            $table->integer('host_id')->comment('站別ID');
            $table->string('code')->index('code')->comment('代碼');
            $table->string('key')->index('key')->comment('索引');
            $table->string('value')->comment('設定值');
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
        Schema::dropIfExists('system_settings');
    }
}
