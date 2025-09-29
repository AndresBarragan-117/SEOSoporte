<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.Ticket', function (Blueprint $table) {
            $table->bigIncrements('idTicket');
            $table->string('guid');
            $table->integer('idTicketPrioridad');
            $table->integer('idTicketEstado');
            $table->integer('idFuncionario');
            $table->integer('idEmpresaClienteUsuario');
            $table->integer('idCategoria');
            $table->string('asunto', 500);
            $table->dateTime('fechaLeido', 0)->nullable();
            $table->integer('usuarioClienteRating');
            $table->string('usuarioClienteComentario', 300);
            $table->string('ultimoReplique', 50);

            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idTicketPrioridad')->references('idTicketPrioridad')->on('Soporte.TicketPrioridad');
            $table->foreign('idTicketEstado')->references('idTicketEstado')->on('Soporte.TicketEstado');
            $table->foreign('idFuncionario')->references('idFuncionario')->on('Soporte.Funcionario');
            $table->foreign('idEmpresaClienteUsuario')->references('idEmpresaClienteUsuario')->on('Soporte.EmpresaClienteUsuario');
            $table->foreign('idCategoria')->references('idCategoria')->on('Soporte.Categoria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.Ticket');
    }
}
