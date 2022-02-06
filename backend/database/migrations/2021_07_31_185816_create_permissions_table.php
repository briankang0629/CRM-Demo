<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('permission_id')->comment('權限ID');
            $table->integer('host_id')->comment('站別ID');
            $table->string('name')->index('name')->comment('權限名稱');
            $table->text('permission')->index('permission')->comment('權限內容');
            $table->enum('status', ['Y','N'])->default('N')->comment('狀態');
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
        Schema::dropIfExists('permissions');
    }
}
