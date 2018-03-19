<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_client')->unsigned();
            $table->foreign('id_client')->references('id')->on('clients')->onDelete('cascade');
            $table->string('street')->comment('Rua');
            $table->text('complement')->comment('Complemento')->nullable();
            $table->integer('number')->comment('Número')->nullable();
            $table->string('neighborhood')->comment('Bairro');
            $table->string('city')->comment('Cidade');
            $table->char('state', 2)->comment('Estado');
            $table->string('zip_code')->comment('CEP');
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
        Schema::dropIfExists('addresses');
    }
}
