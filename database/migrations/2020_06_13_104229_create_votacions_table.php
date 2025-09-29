<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.Votacion', function (Blueprint $table) {
            $table->bigIncrements('idVotacion');
            $table->bigInteger('idUsuario');
            $table->bigInteger('idKBArticulo');
            $table->integer('cantidadVotos');
            $table->timestamps();

            $table->foreign('idUsuario')->references('id')->on('users');
            $table->foreign('idKBArticulo')->references('idKBArticulo')->on('Soporte.KBArticulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soporte.Votacion');
    }
}
