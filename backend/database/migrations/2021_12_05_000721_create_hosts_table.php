<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosts', function (Blueprint $table) {
            $table->increments('host_id')->comment('站別ID');
            $table->string('name')->comment('站別名稱');
            $table->string('domain')->comment('網域名稱');
            $table->string('host_code', 32)->comment('網站代碼');
            $table->string('sub_domain')->comment('子網域名稱');
            $table->string('full_domain')->comment('整個網域名稱');
            $table->string('email')->nullable()->comment('email');
            $table->string('tax')->nullable()->comment('傳真');
            $table->string('mobile')->nullable()->comment('手機');
            $table->integer('commission')->default(0)->comment('佣金（％）');
            $table->enum('status', ['Y', 'N'])->default('N')->comment('啟用狀態');
            $table->dateTime('enable_time')->comment('啟用時間');
            $table->dateTime('disable_time')->comment('禁用時間');
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
        Schema::dropIfExists('hosts');
    }
}
