<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->increments('order_status_id')->comment('訂單狀態ID');
            $table->integer('host_id')->comment('站別ID');
            $table->enum('language', ['zh-tw', 'en-us'])->default('zh-tw')->comment('語系');
            $table->string('name')->nullable()->comment('名稱');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
}
