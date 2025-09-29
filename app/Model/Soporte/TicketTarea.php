<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class TicketTarea extends Model
{
    protected $table = 'Soporte.TicketTarea';
    protected $primaryKey = 'idTicketTarea';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'idTicketTarea','idTicket', 'idFuncionario','fechaInicio','contenidoInicio','fechaFin','contenidoFin','idUsuarioCreacion','idUsuarioModificacion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
