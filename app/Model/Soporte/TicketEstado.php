<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class TicketEstado extends Model
{
    protected $table = 'Soporte.TicketEstado';
    protected $primaryKey = 'idTicketEstado';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'idTicketEstado','nombre', 'orden', 'color', 'idUsuarioCreacion','idUsuarioModificacion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
