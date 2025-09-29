<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioAccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Seguridad.UsuarioAccion', function (Blueprint $table) {
            $table->bigIncrements('idUsuarioAccion');
            $table->bigInteger('idUsuario');
            $table->string('accion', 100);
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

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
        Schema::dropIfExists('Seguridad.UsuarioAccion');
    }
}
