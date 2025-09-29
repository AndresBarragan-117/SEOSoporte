<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionarioCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.FuncionarioCategoria', function (Blueprint $table) {
            $table->increments('idFuncionarioCategoria');
            $table->integer('idCategoria');
            $table->integer('idFuncionario');
            $table->dateTime('fechaCreacion');
            $table->integer('idUsuarioCreacion');
            $table->timestamps();

            $table->foreign('idCategoria')->references('idCategoria')->on('Soporte.Categoria');
            $table->foreign('idFuncionario')->references('idFuncionario')->on('Soporte.Funcionario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.FuncionarioCategoria');
    }
}
