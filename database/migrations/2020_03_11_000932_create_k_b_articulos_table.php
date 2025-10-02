<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKBArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.KBArticulo', function (Blueprint $table) {
            $table->bigIncrements('idKBArticulo');
            $table->bigInteger('idKBArticuloCategoria');
            $table->string('asunto', 250);
            $table->longText('contenido');
            // $table->integer('tipo'); //0=>GRABADO, 1=>PUBLICO, 2=>SOPORTE
            $table->bigInteger('idTipo'); // FK HACIA KBArticuloTipo
            $table->integer('cantidadVistos');
            $table->integer('cantidadVotos');
            $table->integer('cantidadVotacion');

            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idKBArticuloCategoria')->references('idKBArticuloCategoria')->on('Soporte.KBArticuloCategoria');
            $table->foreign('idTipo')->references('idKBArticuloTipo')->on('Soporte.KBArticuloTipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.KBArticulo');
    }
}
