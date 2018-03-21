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
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->decimal('amount', 10, 2)->comment('Valor do imóvel');
            $table->string('type_propertie')->comment('Tipo de imóvel');
            $table->string('neighborhood')->comment('Bairro pretendido');
            $table->char('type', 1)->comment('Proprietário | Interessado | Todos');
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
