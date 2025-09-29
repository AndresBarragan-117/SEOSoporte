<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketPrioridad extends Migration
{
    /** 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Soporte.TicketPrioridad', function (Blueprint $table) {
            $table->increments('idTicketPrioridad');
            $table->string('nombre', 300);
            $table->integer('orden');

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
        Schema::dropIfExists('Soporte.TicketPrioridad');
    }
}
