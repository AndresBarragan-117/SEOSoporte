<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.Funcionario', function (Blueprint $table) {
            $table->increments('idFuncionario');
            $table->integer('idUser');
            $table->string('nombre', 300);
            $table->string('email', 150);
            $table->string('pwd', 200);
            $table->string('firma', 300);
            $table->boolean('estado');
            $table->integer('idUsuarioCreacion');
            $table->integer('idUsuarioModificacion');
            $table->timestamps();

            $table->foreign('idUser')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.Funcionario');
    }
}