<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_totals', function (Blueprint $table) {
            $table->increments('order_total_id')->comment('訂單細項金額ID');
            $table->integer('host_id')->comment('站別ID');
            $table->integer('order_id')->default(0)->comment('訂單ID');
            $table->string('code')->nullable()->comment('代碼');
            $table->string('title')->nullable()->comment('標題');
            $table->decimal('value', 15, 4)->default(0)->comment('金額');
            $table->integer('sort_order')->default(0)->comment('排序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_totals');
    }
}
