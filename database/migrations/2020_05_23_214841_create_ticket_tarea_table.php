<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTareaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.TicketTarea', function (Blueprint $table) {
            $table->bigIncrements('idTicketTarea');
            $table->bigInteger('idTicket');
            $table->integer('idFuncionario');
            $table->dateTime('fechaInicio', 0)->nullable();
            $table->string('contenidoInicio', 500);
            $table->dateTime('fechaFin', 0)->nullable();
            $table->string('contenidoFin', 500)->nullable();
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idTicket')->references('idTicket')->on('Soporte.Ticket');
            $table->foreign('idFuncionario')->references('idFuncionario')->on('Soporte.Funcionario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.TicketTarea');
    }
}