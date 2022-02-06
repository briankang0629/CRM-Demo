<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('currency_id')->comment('幣別ID');
            $table->string('name')->nullable()->comment('名稱');
            $table->string('code')->nullable()->comment('代碼');
            $table->string('symbol')->nullable()->comment('代表符號');
            $table->enum('position', ['left', 'right'])->default('left')->comment('符號位置');
            $table->tinyInteger('decimal_place')->default(0)->comment('小數點後幾位');
            $table->decimal('value', 15, 8)->default('0')->comment('匯率');
            $table->enum('status', ['Y', 'N'])->default('N')->comment('狀態');
            $table->enum('standard', ['Y', 'N'])->default('N')->comment('基準幣');
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
        Schema::dropIfExists('currencies');
    }
}
