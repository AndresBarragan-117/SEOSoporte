<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolFormulariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Seguridad.RolFormulario', function (Blueprint $table) {
            $table->increments('idRolFormulario');
            $table->integer('idRol');
            $table->integer('idFormulario');
            
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();
            
            $table->foreign('idRol')->references('idRol')->on('Seguridad.Rol');
            $table->foreign('idFormulario')->references('idFormulario')->on('Seguridad.Formulario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Seguridad.RolFormulario');
    }
}
