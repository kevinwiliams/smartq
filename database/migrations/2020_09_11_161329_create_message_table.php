<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->string('subject');
            $table->text('message');
            $table->string('attachment', 128)->nullable();
            $table->dateTime('datetime');
            $table->boolean('sender_status')->default(0)->comment('0=unseen, 1=seen, 2=delete');
            $table->boolean('receiver_status')->default(0)->comment('0=unseen, 1=seen, 2=delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}
