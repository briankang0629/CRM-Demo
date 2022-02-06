<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id')->comment('訂單ID');
            $table->integer('host_id')->comment('站別ID');
            $table->string('order_number')->index('order_number')->comment('名稱');
            $table->string('receipt_number')->nullable()->comment('帳單編號');
            $table->string('receipt_prefix')->nullable()->comment('帳單前綴');
            $table->integer('user_id')->default(0)->comment('會員ID');
            $table->integer('user_group_id')->default(0)->comment('會員群組ID');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('email')->nullable()->comment('email');
            $table->string('mobile')->nullable()->comment('手機號碼');
            $table->string('fax')->nullable()->comment('傳真');
            $table->string('payment_method')->nullable()->comment('支付方式');
            $table->string('payment_code')->nullable()->comment('支付代碼');
            $table->string('shipping_method')->nullable()->comment('運送方式');
            $table->string('shipping_code')->nullable()->comment('運送代碼');
            $table->string('payment_name')->nullable()->comment('支付者姓名');
            $table->string('payment_mobile')->nullable()->comment('支付者手機');
            $table->integer('payment_city_id')->default(0)->comment('支付者城市ID');
            $table->integer('payment_zone_id')->default(0)->comment('支付者鄉鎮ID');
            $table->integer('payment_postcode')->default(0)->comment('支付者郵遞區號');
            $table->string('payment_address')->nullable()->comment('支付者地址');
            $table->string('shipping_name')->nullable()->comment('收件者姓名');
            $table->string('shipping_mobile')->nullable()->comment('收件者手機');
            $table->string('shipping_email')->nullable()->comment('收件者email');
            $table->integer('shipping_city_id')->default(0)->comment('收件者城市ID');
            $table->integer('shipping_zone_id')->default(0)->comment('收件者鄉鎮ID');
            $table->integer('shipping_postcode')->default(0)->comment('收件者郵遞區號');
            $table->string('shipping_address')->nullable()->comment('收件者地址');
            $table->text('remark')->nullable()->comment('備註');
            $table->decimal('total', 15, 4)->default(0)->comment('訂單總額');
            $table->integer('order_status_id')->default(0)->comment('訂單狀態ID');
            $table->integer('affiliate_id')->default(0)->comment('聯盟行銷ID');
            $table->decimal('commission', 15, 4)->default(0)->comment('佣金');
            $table->integer('marketing_id')->default(0)->comment('行銷活動ID');
            $table->string('tracking')->nullable()->comment('系統追蹤碼');
            $table->integer('currency_id')->default(0)->comment('匯率ID');
            $table->string('currency_code')->nullable()->comment('匯率代碼');
            $table->decimal('currency_value', 15, 4)->default(0)->comment('訂單成立當下匯率');
            $table->string('ip')->nullable()->comment('IP');
            $table->string('user_agent')->nullable()->comment('使用者瀏覽器');
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
        Schema::dropIfExists('orders');
    }
}
