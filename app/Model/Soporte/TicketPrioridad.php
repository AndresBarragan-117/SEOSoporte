<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class TicketPrioridad extends Model
{
    protected $table = 'Soporte.TicketPrioridad';
    protected $primaryKey = 'idTicketPrioridad';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'idTicketPrioridad','nombre', 'orden','idUsuarioCreacion','idUsuarioModificacion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
