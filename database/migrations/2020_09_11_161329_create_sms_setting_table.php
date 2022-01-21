<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_setting', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('provider', 20)->default('nexmo');
            $table->string('api_key')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('from', 50)->nullable();
            $table->text('sms_template')->nullable();
            $table->text('recall_sms_template')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_setting');
    }
}
