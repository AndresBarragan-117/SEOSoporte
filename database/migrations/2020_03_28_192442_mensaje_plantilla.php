<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MensajePlantilla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.MensajePlantilla', function (Blueprint $table) {
            $table->increments('idMensajePlantilla');
            $table->integer('idCategoria');
            $table->string('pregunta', 300);
            $table->string('respuesta', 300);

            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

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
        Schema::dropIfExists('Soporte.MensajePlantilla');
    }
}
