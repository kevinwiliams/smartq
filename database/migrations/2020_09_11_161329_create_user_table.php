<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname', 25)->nullable();
            $table->string('lastname', 25)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('password')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('photo', 50)->nullable();
            $table->boolean('user_type')->default(1)->comment('1=officer, 2=staff, 3=client, 5=admin');
            $table->string('remember_token')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable();
            $table->boolean('status')->default(1)->comment('1=active,2=inactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
