<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoPlantillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Implementacion.TipoPlantilla', function (Blueprint $table) {
            $table->bigIncrements('idTipoPlantilla');
            $table->string('nombre', 255);
            $table->string('descripcion', 255)->nullable(); // Si puede permitir null
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('idUsuarioCreacion');
            $table->unsignedBigInteger('idUsuarioModificacion');
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
        Schema::dropIfExists('Implementacion.TipoPlantilla');
    }
}
