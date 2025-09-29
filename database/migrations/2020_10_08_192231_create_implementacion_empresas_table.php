<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImplementacionEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Implementacion.ImplementacionEmpresa', function (Blueprint $table) {
            $table->bigIncrements('idImplementacionEmpresa');
            $table->integer('idEmpresa');
            $table->integer('idPlantilla');
            $table->integer('idPlantillaLista');
            $table->integer('valor');
            $table->dateTime('fechaRealiza');
            $table->string('observacion');
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();
           
            $table->foreign('idPlantilla')->references('idPlantilla')->on('Implementacion.Plantilla');
            $table->foreign('idPlantillaLista')->references('idPlantillaLista')->on('Implementacion.PlantillaLista');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ImplementacionEmpresa');
    }
}
