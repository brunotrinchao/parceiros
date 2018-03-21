<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesBuyStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties__buy__statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('properties_id')->unsigned();
            $table->foreign('properties_id')->references('id')->on('properties')->onDelete('cascade');
            $table->char('status')->default('A')->comment('Status - A:Aguardando contato | B:Telefone errado | C:Desistiu | D:Négocio fechado | E:Em andamento');
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
        Schema::dropIfExists('properties__buy__statuses');
    }
}
