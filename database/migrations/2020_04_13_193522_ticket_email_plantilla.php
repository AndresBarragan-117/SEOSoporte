<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketEmailPlantilla extends Migration
{
    /** 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.TicketEmailPlantilla', function (Blueprint $table) {
            $table->increments('idTicketEmailPlantilla');
            $table->integer('idTicketEstado');
            $table->string('asunto', 150);
            $table->string('contenido', 300);

            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

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
        Schema::dropIfExists('Soporte.TicketEmailPlantilla');
    }
}
