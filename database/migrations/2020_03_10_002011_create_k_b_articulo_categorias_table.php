<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKBArticuloCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.KBArticuloCategoria', function (Blueprint $table) {
            $table->bigIncrements('idKBArticuloCategoria');
            $table->string('nombre');
            $table->integer('padreId')->nullable();
            $table->string('nombreEtiqueta');
            $table->integer('orden');
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('padreId')->references('idKBArticuloCategoria')->on('Soporte.KBArticuloCategoria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.KBArticuloCategoria');
    }
}
