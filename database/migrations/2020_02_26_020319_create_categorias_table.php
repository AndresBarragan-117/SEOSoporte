<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.Categoria', function (Blueprint $table) {
            $table->increments('idCategoria');
            $table->string('codigo', 20);
            $table->string('nombre', 300);
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
        Schema::dropIfExists('Soporte.Categoria');
    }
}