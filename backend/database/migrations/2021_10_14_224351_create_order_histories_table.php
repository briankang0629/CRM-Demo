<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->increments('order_history_id')->comment('訂單的商品選項ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('order_id')->default(0)->comment('訂單ID');
            $table->integer('order_status_id')->default(0)->comment('訂單狀態ID');
            $table->enum('notify', ['Y', 'N'])->default('N')->comment('是否已通知');
            $table->text('comment')->nullable()->comment('備註');
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
        Schema::dropIfExists('order_histories');
    }
}
