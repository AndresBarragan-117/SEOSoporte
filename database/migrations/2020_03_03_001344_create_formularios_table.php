<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Seguridad.Formulario', function (Blueprint $table) {
            $table->increments('idFormulario');
            $table->integer('idCarpeta');
            $table->string('nombre');
            $table->string('path');
            $table->integer('tag')->unique();
            $table->boolean('widget');
            $table->boolean('estado');

            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idCarpeta')->references('idCarpeta')->on('Seguridad.Carpeta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Seguridad.Formulario');
    }
}
