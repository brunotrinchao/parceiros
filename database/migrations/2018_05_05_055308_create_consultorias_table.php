<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultorias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('partner_id')->unsigned();
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->decimal('renda_comprovada', 10, 2)->comment('Renda comprovada');
            $table->decimal('valor_bem', 10, 2)->comment('Valor do bem');
            $table->decimal('valor_financiado', 10, 2)->comment('Valor financiado');
            $table->text('note')->comment('Observação');
            $table->char('type', 1)->comment('I:Imóveis | V:Veículos');
            $table->date('date');
            $table->string('status')->comment('Status');
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
        Schema::dropIfExists('consultorias');
    }
}
