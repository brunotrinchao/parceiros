<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id')->unsigned();
            $table->integer('partners_id')->unsigned();
            $table->foreign('partners_id')->references('id')->on('partners');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->default('default.jpg');;
            $table->date('date');
            $table->char('level', 1)->default('U')->comment('Nivel de acesso | S:SuperAdmin - P:Parceiro - G:Gerente - U:Usuario');
            $table->char('status', 1)->default('A')->nullable();;
            $table->string('contact')->nullable();
            $table->tinyInteger('is_permission');
            $table->rememberToken();
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
