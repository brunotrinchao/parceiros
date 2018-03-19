<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_client')->unsigned();
            $table->foreign('id_client')->references('id')->on('clients');
            $table->decimal('amount', 10, 2)->comment('Valor do imóvel')->unique();
            $table->decimal('input', 10, 2)->comment('Valor da entrada');
            $table->integer('plots')->comment('Parcelas pretendida');
            $table->integer('deadline')->comment('Prazo')->unique();
            $table->char('type', 1)->comment('Proprietário | Interessado');
            $table->text('note')->comment('Observação');
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
        Schema::dropIfExists('properties');
    }
}
