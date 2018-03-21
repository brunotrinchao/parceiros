<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name')->comment('Nome');
            $table->string('email')->comment('E-mail')->nullable();
            $table->date('birth')->comment('Data de aniversário');
            $table->char('sex', 1)->comment('Sexo=M:Masculino|F:Feminino')->nullable();
            $table->char('type', 1)->comment('Tipo=F:Física|J:Juridica');
            $table->string('cpf_cnpj')->comment('CPF | CNPJ')->unique();
            $table->string('contact')->comment('Nome do contato')->nullable();
            $table->integer('n_officials')->comment('Número de funcionários')->nullable();
            $table->date('date')->comment('Data de cadastro');
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
        Schema::dropIfExists('clients');
    }
}
