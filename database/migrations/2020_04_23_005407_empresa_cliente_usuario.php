<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmpresaClienteUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.EmpresaClienteUsuario', function (Blueprint $table) {
            $table->increments('idEmpresaClienteUsuario');
            $table->string('nombre', 150);
            $table->string('email', 200);
            $table->string('pwd', 300);
            $table->integer('idEmpresaCliente');

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
        Schema::dropIfExists('Soporte.EmpresaClienteUsuario');
    }
}
