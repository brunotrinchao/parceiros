<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanciamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financiamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->char('type')->comment('T:Tradicional | R:Refinanciamento');
            $table->decimal('valor_bem', 10, 2)->comment('Valor do bem');
            $table->decimal('renda_comprovada', 10, 2)->comment('Valor da renda');
            $table->decimal('valor_financiamento', 10, 2)->comment('Valor do financiamento');
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
        Schema::dropIfExists('financiamentos');
    }
}
