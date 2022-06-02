<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('department_id');
            $table->integer('position_id');
            $table->string('fin_code');
            $table->string('serial_number');
            $table->string('serial_code');
            $table->string('name');
            $table->string('surname');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('password');
            $table->integer('role')->default(Roles::USER);
            $table->integer('status')->default(Status::ACTIVE);
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
        Schema::dropIfExists('users');
    }
}
