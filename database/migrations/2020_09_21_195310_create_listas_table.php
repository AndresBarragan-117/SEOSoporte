<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Implementacion.PlantillaLista', function (Blueprint $table) {
            $table->bigIncrements('idPlantillaLista');
            $table->string('nombre');
            $table->string('descripcion');
            $table->integer('idPlantilla');
            $table->string('numeroOrdenLista');
            $table->integer('idTipoCaptura');
            $table->string('opcionTipoCaptura');
            $table->boolean('estado');
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();
           
            $table->foreign('idPlantilla')->references('idPlantilla')->on('Implementacion.Plantilla');
            $table->foreign('idTipoCaptura')->references('idTipoCaptura')->on('Implementacion.TipoCaptura');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listas');
    }
}
