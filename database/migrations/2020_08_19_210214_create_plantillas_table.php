<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Implementacion.Plantilla', function (Blueprint $table) {
            $table->bigIncrements('idPlantilla');
            $table->integer('idTipoPlantilla');
            $table->string('nombre');
            $table->string('descripcion');
            $table->boolean('estado');
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idTipoPlantilla')->references('idTipoPlantilla')->on('Implementacion.TipoPlantilla');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Implementacion.Plantilla');
    }
}
