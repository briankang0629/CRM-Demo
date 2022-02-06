<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_menus', function (Blueprint $table) {
            $table->increments('system_menu_id')->comment('選單ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('parent_id')->comment('上層ID');
            $table->string('code')->comment('代碼');
            $table->string('route')->comment('route');
            $table->string('icon')->comment('圖示');
            $table->smallInteger('sort_order')->default(0)->index('sort_order')->comment('排序');
            $table->enum('status', ['Y','N'])->default('N')->comment('狀態');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_menus');
    }
}
