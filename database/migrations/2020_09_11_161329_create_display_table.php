<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisplayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('display', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('message')->nullable();
            $table->string('direction', 10)->nullable()->default('left');
            $table->string('color', 10)->nullable()->default('#ffffff');
            $table->string('background_color', 10)->default('#cdcdcd');
            $table->string('border_color', 10)->default('#ffffff');
            $table->string('time_format', 20)->nullable()->default('h:i:s A');
            $table->string('date_format', 50)->nullable()->default('d M, Y');
            $table->dateTime('updated_at')->nullable();
            $table->boolean('display')->default(1)->comment('1-single, 2/3-counter,4-department,5-hospital');
            $table->boolean('keyboard_mode')->default(1)->comment('0-inactive,1-active');
            $table->boolean('sms_alert')->default(1)->comment('0-inactive, 1-active ');
            $table->boolean('show_note')->default(0)->comment('0-inactive, 1-active ');
            $table->boolean('show_officer')->default(1);
            $table->boolean('show_department')->default(1);
            $table->integer('alert_position')->default(3);
            $table->string('language', 20)->nullable()->default('English');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('display');
    }
}
