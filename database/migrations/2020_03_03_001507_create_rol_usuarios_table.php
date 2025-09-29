<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Seguridad.RolUsuario', function (Blueprint $table) {
            $table->increments('idRolUsuario');
            $table->integer('idRol');
            $table->integer('idUsuario');

            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idRol')->references('idRol')->on('Seguridad.Rol');
            $table->foreign('idUsuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Seguridad.RolUsuario');
    }
}
