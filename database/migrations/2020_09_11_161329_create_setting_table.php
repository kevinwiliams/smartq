<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 100);
            $table->string('description')->nullable();
            $table->string('logo', 50)->nullable();
            $table->string('favicon', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 16)->nullable();
            $table->string('address')->nullable();
            $table->string('copyright_text')->nullable();
            $table->string('direction', 10)->nullable();
            $table->string('language', 10)->nullable();
            $table->string('timezone', 32)->default('Asia/Dhaka');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
