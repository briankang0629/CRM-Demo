<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 允許客戶一個站多網址
        Schema::create('custom_domains', function (Blueprint $table) {
            $table->increments('custom_domain_id')->comment('客製化Domain id');
            $table->integer('host_id')->unsigned()->comment('站別ID');
            $table->string('domain')->comment('客製化網址');
            $table->enum('is_share', ['Y', 'N'])->comment('是否共用網址');
            $table->timestamps();

            $table->unique(['host_id', 'domain'], 'host_domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_domains');
    }
}
