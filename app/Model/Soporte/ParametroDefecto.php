<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class ParametroDefecto extends Model
{
    protected $table = 'Soporte.ParametroDefecto';
    protected $primaryKey = 'idParametroDefecto';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'idParametroDefecto','idTicketPrioridad','idTicketEstado', 'idFuncionario','idTicketEstadoFinalizar','idTicketEstadoArchivar','idTicketEstadoRechazar','diasArchivar','idUsuarioCreacion','idUsuarioModificacion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
