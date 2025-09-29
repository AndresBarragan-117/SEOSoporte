<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class TicketMensaje extends Model
{
    protected $table = 'Soporte.TicketMensaje';
    protected $primaryKey = 'idTicketMensaje';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'idTicketMensaje','idTicket','fechaLeido','contenido','archivoNombre1', 'archivoAnexo1','archivoNombre2','archivoAnexo2','archivoNombre3','archivoAnexo3','idFuncionario','tipo','idTicketEstado','nota','idUsuarioCreacion','idUsuarioModificacion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}