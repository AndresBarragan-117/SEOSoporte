<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoCapturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Implementacion.TipoCaptura', function (Blueprint $table) {
            $table->bigIncrements('idTipoCaptura');
            $table->string('nombre');
            $table->string('descripcion');
            $table->boolean('estado');
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
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
        Schema::dropIfExists('Implementacion.TipoCaptura');
    }
}
