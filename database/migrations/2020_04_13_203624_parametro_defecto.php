<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParametroDefecto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.ParametroDefecto', function (Blueprint $table) {
            $table->increments('idParametroDefecto');

            $table->integer('idTicketPrioridad');
            $table->integer('idTicketEstado');
            $table->integer('idFuncionario');
            $table->integer('idTicketEstadoFinalizar');
            $table->integer('idTicketEstadoArchivar');
            $table->integer('idTicketEstadoRechazar');
            $table->integer('diasArchivar');
            
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idTicketPrioridad')->references('idTicketPrioridad')->on('Soporte.TicketPrioridad');
            $table->foreign('idTicketEstado')->references('idTicketEstado')->on('Soporte.TicketEstado');
            $table->foreign('idFuncionario')->references('idFuncionario')->on('Soporte.Funcionario');
            $table->foreign('idTicketEstadoFinalizar')->references('idTicketEstado')->on('Soporte.TicketEstado');
            $table->foreign('idTicketEstadoArchivar')->references('idTicketEstado')->on('Soporte.TicketEstado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.ParametroDefecto');
    }
}
