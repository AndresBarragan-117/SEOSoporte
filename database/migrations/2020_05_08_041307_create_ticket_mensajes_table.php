<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketMensajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.TicketMensaje', function (Blueprint $table) {
            $table->bigIncrements('idTicketMensaje');
            $table->bigInteger('idTicket');
            $table->dateTime('fechaLeido', 0)->nullable();
            $table->string('contenido', 500);
            $table->string('archivoNombre1')->nullable();
            $table->binary('archivoAnexo1')->nullable();
            $table->string('archivoNombre2')->nullable();
            $table->binary('archivoAnexo2')->nullable();
            $table->string('archivoNombre3')->nullable();
            $table->binary('archivoAnexo3')->nullable();
            $table->integer('idFuncionario');
            $table->string('tipo'); //REQUERIDO, RESPUESTA
            $table->integer('idTicketEstado');
            $table->string('nota');
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idTicket')->references('idTicket')->on('Soporte.Ticket');
            $table->foreign('idFuncionario')->references('idFuncionario')->on('Soporte.Funcionario');
            $table->foreign('idTicketEstado')->references('idTicketEstado')->on('Soporte.TicketEstado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.TicketMensaje');
    }
}